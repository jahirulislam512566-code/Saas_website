<?php

namespace App\Listeners\Payment;

use App\Events\Payment\PaymentReceived;
use App\Mail\Payment\ReceiptMail;
use App\Listeners\BaseListener;
use Illuminate\Support\Facades\Mail;

class SendReceiptEmail extends BaseListener
{
    public function handle(PaymentReceived $event): void
    {
        $this->logStart('PaymentReceived', [
            'payment_id' => $event->payment->id,
            'order_id' => $event->payment->order_id,
        ]);

        try {
            Mail::to($event->payment->order->customer->email)
                ->send(new ReceiptMail($event->payment));

            $this->logComplete('PaymentReceived', ['payment_id' => $event->payment->id]);
        } catch (\Exception $e) {
            $this->logError('PaymentReceived', $e, ['payment_id' => $event->payment->id]);
        }
    }
}