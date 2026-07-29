<?php

namespace App\Services\Payment;

use App\Services\Payment\Gateways\StripeGateway;
use App\Services\Payment\Gateways\PayPalGateway;
use App\Services\Payment\Gateways\SSLCommerzGateway;
use App\Services\Payment\DTOs\PaymentRequestDTO;
use App\Services\Payment\DTOs\PaymentResponseDTO;
use App\Services\Payment\Exceptions\PaymentGatewayException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PaymentService
{
    protected array $gateways = [];
    protected string $defaultGateway;

    public function __construct(array $config = [])
    {
        $this->defaultGateway = $config['default'] ?? config('payment.default', 'stripe');
        $this->initializeGateways($config['gateways'] ?? config('payment.gateways', []));
    }

    /**
     * Initialize payment gateways.
     */
    protected function initializeGateways(array $gatewayConfigs): void
    {
        foreach ($gatewayConfigs as $name => $config) {
            if (!$config['enabled'] ?? true) {
                continue;
            }

            $gateway = $this->createGateway($name, $config);
            if ($gateway) {
                $this->gateways[$name] = $gateway;
            }
        }
    }

    /**
     * Create gateway instance.
     */
    protected function createGateway(string $name, array $config): ?PaymentGatewayInterface
    {
        return match ($name) {
            'stripe' => new StripeGateway($config),
            'paypal' => new PayPalGateway($config),
            'sslcommerz' => new SSLCommerzGateway($config),
            default => null,
        };
    }

    /**
     * Process payment using specified gateway.
     */
    public function processPayment(PaymentRequestDTO $request, ?string $gatewayName = null): PaymentResponseDTO
    {
        $gatewayName = $gatewayName ?? $this->defaultGateway;
        $gateway = $this->getGateway($gatewayName);

        if (!$gateway) {
            throw new PaymentGatewayException("Payment gateway '{$gatewayName}' not found.");
        }

        try {
            // Validate currency support
            if (!in_array($request->currency, $gateway->getSupportedCurrencies())) {
                throw new PaymentGatewayException(
                    "Currency {$request->currency} is not supported by {$gatewayName}."
                );
            }

            // Log payment attempt
            Log::info('Processing payment', [
                'gateway' => $gatewayName,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'order_id' => $request->orderId,
            ]);

            // Process payment
            $response = $gateway->processPayment($request->toArray());

            // Cache payment response for verification
            Cache::put("payment_{$response->transactionId}", $response, 3600);

            // Log payment result
            Log::info('Payment processed', [
                'gateway' => $gatewayName,
                'transaction_id' => $response->transactionId,
                'status' => $response->status,
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'gateway' => $gatewayName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new PaymentGatewayException(
                'Payment processing failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Verify a payment.
     */
    public function verifyPayment(string $transactionId, ?string $gatewayName = null): PaymentResponseDTO
    {
        $gatewayName = $gatewayName ?? $this->defaultGateway;
        $gateway = $this->getGateway($gatewayName);

        if (!$gateway) {
            throw new PaymentGatewayException("Payment gateway '{$gatewayName}' not found.");
        }

        try {
            $response = $gateway->verifyPayment($transactionId);

            // Update cache
            Cache::put("payment_{$transactionId}", $response, 3600);

            return $response;

        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'gateway' => $gatewayName,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            throw new PaymentGatewayException(
                'Payment verification failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Refund a payment.
     */
    public function refundPayment(string $transactionId, float $amount, ?string $gatewayName = null): PaymentResponseDTO
    {
        $gatewayName = $gatewayName ?? $this->defaultGateway;
        $gateway = $this->getGateway($gatewayName);

        if (!$gateway) {
            throw new PaymentGatewayException("Payment gateway '{$gatewayName}' not found.");
        }

        try {
            $response = $gateway->refundPayment($transactionId, $amount);

            Log::info('Payment refunded', [
                'gateway' => $gatewayName,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'status' => $response->status,
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('Payment refund failed', [
                'gateway' => $gatewayName,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            throw new PaymentGatewayException(
                'Payment refund failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Get payment status.
     */
    public function getPaymentStatus(string $transactionId, ?string $gatewayName = null): PaymentResponseDTO
    {
        $gatewayName = $gatewayName ?? $this->defaultGateway;
        $gateway = $this->getGateway($gatewayName);

        if (!$gateway) {
            throw new PaymentGatewayException("Payment gateway '{$gatewayName}' not found.");
        }

        return $gateway->getPaymentStatus($transactionId);
    }

    /**
     * Get gateway instance.
     */
    protected function getGateway(string $name): ?PaymentGatewayInterface
    {
        return $this->gateways[$name] ?? null;
    }

    /**
     * Get all available gateways.
     */
    public function getAvailableGateways(): array
    {
        return array_keys($this->gateways);
    }

    /**
     * Get default gateway.
     */
    public function getDefaultGateway(): string
    {
        return $this->defaultGateway;
    }

    /**
     * Set default gateway.
     */
    public function setDefaultGateway(string $gatewayName): void
    {
        if (!isset($this->gateways[$gatewayName])) {
            throw new PaymentGatewayException("Gateway '{$gatewayName}' not found.");
        }

        $this->defaultGateway = $gatewayName;
    }
}