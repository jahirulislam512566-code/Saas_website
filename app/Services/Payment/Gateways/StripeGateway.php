<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\DTOs\PaymentResponseDTO;
use App\Services\Payment\Exceptions\PaymentGatewayException;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;

class StripeGateway implements PaymentGatewayInterface
{
    protected array $config;
    protected string $apiKey;
    protected string $webhookSecret;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->apiKey = $config['secret_key'] ?? config('services.stripe.secret');
        $this->webhookSecret = $config['webhook_secret'] ?? config('services.stripe.webhook_secret');
        
        Stripe::setApiKey($this->apiKey);
    }

    public function initialize(array $config): void
    {
        $this->config = array_merge($this->config, $config);
        Stripe::setApiKey($this->config['secret_key'] ?? $this->apiKey);
    }

    public function processPayment(array $data): PaymentResponseDTO
    {
        try {
            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToCents($data['amount']),
                'currency' => strtolower($data['currency'] ?? 'usd'),
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $data['order_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                ],
                'description' => $data['description'] ?? 'Payment',
                'customer' => $data['customer_stripe_id'] ?? null,
                'receipt_email' => $data['receipt_email'] ?? null,
            ]);

            // Return response
            return new PaymentResponseDTO([
                'success' => true,
                'transactionId' => $paymentIntent->id,
                'status' => $this->mapStatus($paymentIntent->status),
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'USD',
                'paymentMethod' => 'card',
                'gateway' => 'stripe',
                'rawResponse' => $paymentIntent->toArray(),
                'redirectUrl' => $paymentIntent->next_action?->redirect_to_url?->url ?? null,
                'clientSecret' => $paymentIntent->client_secret,
            ]);

        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                'Stripe error: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function verifyPayment(string $transactionId): PaymentResponseDTO
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($transactionId);

            return new PaymentResponseDTO([
                'success' => true,
                'transactionId' => $paymentIntent->id,
                'status' => $this->mapStatus($paymentIntent->status),
                'amount' => $this->convertFromCents($paymentIntent->amount),
                'currency' => strtoupper($paymentIntent->currency),
                'paymentMethod' => $paymentIntent->payment_method_types[0] ?? 'card',
                'gateway' => 'stripe',
                'rawResponse' => $paymentIntent->toArray(),
            ]);

        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                'Stripe verification failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function refundPayment(string $transactionId, float $amount): PaymentResponseDTO
    {
        try {
            $refund = Refund::create([
                'payment_intent' => $transactionId,
                'amount' => $this->convertToCents($amount),
            ]);

            return new PaymentResponseDTO([
                'success' => true,
                'transactionId' => $refund->id,
                'status' => 'refunded',
                'amount' => $amount,
                'currency' => strtoupper($refund->currency),
                'paymentMethod' => 'refund',
                'gateway' => 'stripe',
                'rawResponse' => $refund->toArray(),
            ]);

        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                'Stripe refund failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function getPaymentStatus(string $transactionId): PaymentResponseDTO
    {
        return $this->verifyPayment($transactionId);
    }

    public function getSupportedCurrencies(): array
    {
        return [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'INR',
            'SGD', 'CHF', 'SEK', 'NOK', 'DKK', 'NZD', 'MXN',
        ];
    }

    public function getGatewayName(): string
    {
        return 'stripe';
    }

    protected function mapStatus(string $stripeStatus): string
    {
        return match ($stripeStatus) {
            'succeeded' => 'completed',
            'processing' => 'processing',
            'requires_payment_method' => 'pending',
            'requires_confirmation' => 'pending',
            'requires_action' => 'pending',
            'canceled' => 'cancelled',
            'failed' => 'failed',
            default => 'unknown',
        };
    }

    protected function convertToCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    protected function convertFromCents(int $amount): float
    {
        return $amount / 100;
    }

    /**
     * Create a subscription.
     */
    public function createSubscription(array $data): array
    {
        try {
            $subscription = \Stripe\Subscription::create([
                'customer' => $data['customer_stripe_id'],
                'items' => [
                    ['price' => $data['price_id']],
                ],
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'order_id' => $data['order_id'] ?? null,
                ],
            ]);

            return [
                'subscription_id' => $subscription->id,
                'client_secret' => $subscription->latest_invoice->payment_intent->client_secret,
                'status' => $subscription->status,
            ];

        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                'Subscription creation failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Cancel a subscription.
     */
    public function cancelSubscription(string $subscriptionId): array
    {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);
            $subscription->cancel();

            return [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
            ];

        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                'Subscription cancellation failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Create a customer.
     */
    public function createCustomer(array $data): string
    {
        try {
            $customer = \Stripe\Customer::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'metadata' => [
                    'user_id' => $data['user_id'] ?? null,
                ],
            ]);

            return $customer->id;

        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                'Customer creation failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}