<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\DTOs\PaymentResponseDTO;
use App\Services\Payment\Exceptions\PaymentGatewayException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerzGateway implements PaymentGatewayInterface
{
    protected array $config;
    protected string $baseUrl;
    protected string $storeId;
    protected string $storePassword;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->storeId = $config['store_id'] ?? config('services.sslcommerz.store_id');
        $this->storePassword = $config['store_password'] ?? config('services.sslcommerz.store_password');
        $this->baseUrl = $config['sandbox'] ?? true
            ? 'https://sandbox.sslcommerz.com'
            : 'https://secure.sslcommerz.com';
    }

    public function initialize(array $config): void
    {
        $this->config = array_merge($this->config, $config);
        $this->storeId = $config['store_id'] ?? $this->storeId;
        $this->storePassword = $config['store_password'] ?? $this->storePassword;
        $this->baseUrl = ($config['sandbox'] ?? true)
            ? 'https://sandbox.sslcommerz.com'
            : 'https://secure.sslcommerz.com';
    }

    public function processPayment(array $data): PaymentResponseDTO
    {
        try {
            $postData = [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'total_amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'BDT',
                'tran_id' => $data['transaction_id'] ?? $this->generateTransactionId(),
                'success_url' => $data['success_url'] ?? config('payment.sslcommerz.success_url'),
                'fail_url' => $data['fail_url'] ?? config('payment.sslcommerz.fail_url'),
                'cancel_url' => $data['cancel_url'] ?? config('payment.sslcommerz.cancel_url'),
                'ipn_url' => $data['ipn_url'] ?? config('payment.sslcommerz.ipn_url'),
                'cus_name' => $data['customer_name'] ?? '',
                'cus_email' => $data['customer_email'] ?? '',
                'cus_phone' => $data['customer_phone'] ?? '',
                'cus_add1' => $data['customer_address'] ?? '',
                'cus_city' => $data['customer_city'] ?? '',
                'cus_country' => $data['customer_country'] ?? 'Bangladesh',
                'shipping_method' => $data['shipping_method'] ?? 'NO',
                'product_name' => $data['product_name'] ?? 'Payment',
                'product_category' => $data['product_category'] ?? 'General',
                'product_profile' => $data['product_profile'] ?? 'general',
                'value_a' => $data['order_id'] ?? null,
                'value_b' => $data['customer_id'] ?? null,
            ];

            $response = Http::post($this->baseUrl . '/gwprocess/v4/api.php', $postData);

            if (!$response->successful()) {
                throw new PaymentGatewayException('SSLCommerz API request failed');
            }

            $result = $response->json();

            if ($result['status'] !== 'SUCCESS') {
                throw new PaymentGatewayException(
                    $result['failed_reason'] ?? 'SSLCommerz payment initialization failed'
                );
            }

            return new PaymentResponseDTO([
                'success' => true,
                'transactionId' => $postData['tran_id'],
                'status' => 'pending',
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'BDT',
                'paymentMethod' => 'sslcommerz',
                'gateway' => 'sslcommerz',
                'rawResponse' => $result,
                'redirectUrl' => $result['GatewayPageURL'],
            ]);

        } catch (\Exception $e) {
            throw new PaymentGatewayException(
                'SSLCommerz payment failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public function verifyPayment(string $transactionId): PaymentResponseDTO
    {
        try {
            $response = Http::post($this->baseUrl . '/validator/api/validationserverAPI.php', [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'tran_id' => $transactionId,
                'format' => 'json',
            ]);

            if (!$response->successful()) {
                throw new PaymentGatewayException('SSLCommerz verification failed');
            }

            $result = $response->json();

            return new PaymentResponseDTO([
                'success' => true,
                'transactionId' => $transactionId,
                'status' => $this->mapStatus($result['status'] ?? ''),
                'amount' => $result['amount'] ?? 0,
                'currency' => $result['currency'] ?? 'BDT',
                'paymentMethod' => 'sslcommerz',
                'gateway' => 'sslcommerz',
                'rawResponse' => $result,
            ]);

        } catch (\Exception $e) {
            throw new PaymentGatewayException(
                'SSLCommerz verification failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public function refundPayment(string $transactionId, float $amount): PaymentResponseDTO
    {
        // SSLCommerz refund process
        // Implementation would depend on SSLCommerz refund API
        throw new PaymentGatewayException('SSLCommerz refund not implemented');
    }

    public function getPaymentStatus(string $transactionId): PaymentResponseDTO
    {
        return $this->verifyPayment($transactionId);
    }

    public function getSupportedCurrencies(): array
    {
        return ['BDT', 'USD', 'EUR', 'GBP', 'INR'];
    }

    public function getGatewayName(): string
    {
        return 'sslcommerz';
    }

    protected function generateTransactionId(): string
    {
        return 'TXN_' . time() . '_' . uniqid();
    }

    protected function mapStatus(string $status): string
    {
        return match ($status) {
            'VALID' => 'completed',
            'VALIDATED' => 'completed',
            'PROCESSING' => 'processing',
            'PENDING' => 'pending',
            'FAILED' => 'failed',
            'CANCELLED' => 'cancelled',
            default => 'unknown',
        };
    }

    /**
     * Validate IPN request.
     */
    public function validateIpn(array $data): bool
    {
        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'tran_id' => $data['tran_id'] ?? '',
        ];

        $response = Http::post($this->baseUrl . '/validator/api/merchantTransIDvalidationAPI.php', $postData);

        if (!$response->successful()) {
            return false;
        }

        $result = $response->json();
        return $result['status'] === 'VALID';
    }

    /**
     * Handle IPN response.
     */
    public function handleIpn(array $data): array
    {
        Log::info('SSLCommerz IPN received', $data);

        if (!$this->validateIpn($data)) {
            return ['status' => 'failed', 'message' => 'Invalid IPN request'];
        }

        // Process IPN data
        $status = $this->mapStatus($data['status'] ?? '');

        return [
            'status' => 'success',
            'transaction_id' => $data['tran_id'],
            'payment_status' => $status,
            'data' => $data,
        ];
    }
}