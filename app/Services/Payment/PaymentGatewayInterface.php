<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initialize payment gateway.
     */
    public function initialize(array $config): void;

    /**
     * Process a payment.
     */
    public function processPayment(array $data): PaymentResponseDTO;

    /**
     * Verify a payment.
     */
    public function verifyPayment(string $transactionId): PaymentResponseDTO;

    /**
     * Refund a payment.
     */
    public function refundPayment(string $transactionId, float $amount): PaymentResponseDTO;

    /**
     * Get payment status.
     */
    public function getPaymentStatus(string $transactionId): PaymentResponseDTO;

    /**
     * Get supported currencies.
     */
    public function getSupportedCurrencies(): array;

    /**
     * Get gateway name.
     */
    public function getGatewayName(): string;
}