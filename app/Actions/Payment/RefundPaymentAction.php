<?php

namespace App\Actions\Payment;

use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundPaymentAction
{
    protected PaymentGatewayService $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Refund a payment
     *
     * @param Payment $payment
     * @param array $data
     * @return Payment
     * @throws \Exception
     */
    public function execute(Payment $payment, array $data = []): Payment
    {
        return DB::transaction(function () use ($payment, $data) {
            // Validate payment can be refunded
            if ($payment->status !== 'completed') {
                throw new \Exception('Only completed payments can be refunded.');
            }

            if ($payment->refunded_at) {
                throw new \Exception('Payment has already been refunded.');
            }

            // Get gateway
            $gateway = PaymentGateway::where('code', $payment->gateway)
                ->where('is_active', true)
                ->first();

            if (!$gateway) {
                throw new \Exception('Payment gateway not available.');
            }

            // Process refund
            $refundAmount = $data['amount'] ?? $payment->amount;
            $refundReason = $data['reason'] ?? 'Customer requested refund';

            try {
                $result = $this->paymentService->refund(
                    $gateway,
                    $payment->gateway_reference,
                    $refundAmount,
                    $refundReason
                );
            } catch (\Exception $e) {
                Log::error('Payment refund failed', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Payment refund failed: ' . $e->getMessage());
            }

            // Update payment
            $payment->update([
                'refunded_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_reason' => $refundReason,
                'refund_data' => $result,
                'status' => 'refunded',
            ]);

            // Update order if exists
            if ($payment->order) {
                $payment->order->update([
                    'payment_status' => 'refunded',
                    'updated_by' => auth()->id(),
                ]);
            }

            Log::warning('Payment refunded', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'refund_amount' => $refundAmount,
                'reason' => $refundReason
            ]);

            return $payment;
        });
    }
}