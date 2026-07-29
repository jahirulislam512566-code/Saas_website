<?php

namespace App\Actions\Payment;

use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Support\Facades\Log;

class VerifyPaymentAction
{
    protected PaymentGatewayService $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Verify a payment
     *
     * @param Payment $payment
     * @return Payment
     * @throws \Exception
     */
    public function execute(Payment $payment): Payment
    {
        // Check if payment is already verified
        if ($payment->verified_at) {
            return $payment;
        }

        // Get gateway
        $gateway = PaymentGateway::where('code', $payment->gateway)
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            throw new \Exception('Payment gateway not available.');
        }

        // Verify with gateway
        try {
            $verification = $this->paymentService->verify(
                $gateway,
                $payment->gateway_reference
            );
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Payment verification failed: ' . $e->getMessage());
        }

        // Update payment
        $payment->update([
            'verified_at' => now(),
            'verification_data' => $verification,
            'status' => $verification['status'] ?? $payment->status,
        ]);

        Log::info('Payment verified', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'status' => $payment->status
        ]);

        return $payment;
    }
}