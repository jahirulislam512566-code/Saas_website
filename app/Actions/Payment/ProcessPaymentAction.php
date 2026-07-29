<?php

namespace App\Actions\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\PaymentGatewayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPaymentAction
{
    protected PaymentGatewayService $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Process a payment
     *
     * @param Order $order
     * @param array $data
     * @return Payment
     * @throws \Exception
     */
    public function execute(Order $order, array $data): Payment
    {
        return DB::transaction(function () use ($order, $data) {
            // Validate payment can be processed
            if ($order->payment_status === 'paid') {
                throw new \Exception('Order is already paid.');
            }

            // Get payment gateway
            $gateway = PaymentGateway::where('code', $data['gateway'] ?? 'stripe')
                ->where('is_active', true)
                ->first();

            if (!$gateway) {
                throw new \Exception('Payment gateway not available.');
            }

            // Process payment
            $paymentData = [
                'amount' => $order->total,
                'currency' => $order->currency ?? 'USD',
                'payment_method' => $data['payment_method'] ?? 'card',
                'customer_email' => $order->customer->email,
                'customer_name' => $order->customer->name,
                'description' => "Order #{$order->order_number}",
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_id' => $order->customer_id,
                ],
            ];

            try {
                $result = $this->paymentService->process($gateway, $paymentData);
            } catch (\Exception $e) {
                Log::error('Payment processing failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Payment processing failed: ' . $e->getMessage());
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'currency' => $order->currency ?? 'USD',
                'payment_method' => $data['payment_method'] ?? 'card',
                'gateway' => $gateway->code,
                'gateway_reference' => $result['transaction_id'],
                'status' => 'completed',
                'paid_at' => now(),
                'payment_data' => $result,
                'created_by' => auth()->id(),
            ]);

            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $data['payment_method'] ?? 'card',
                'updated_by' => auth()->id(),
            ]);

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $payment->amount,
                'gateway' => $gateway->code,
                'transaction_id' => $result['transaction_id']
            ]);

            return $payment;
        });
    }
}