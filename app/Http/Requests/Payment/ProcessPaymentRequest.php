<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\BaseRequest;

class ProcessPaymentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'payment_method' => ['required', 'string', 'in:card,bank,paypal,crypto'],
            'gateway' => ['nullable', 'string', 'in:stripe,paypal,razorpay'],
            'amount' => ['nullable', 'numeric', 'min:0.01'],
            'currency' => ['nullable', 'string', 'max:3'],
            'card_token' => ['required_if:payment_method,card', 'string'],
            'card_holder_name' => ['required_if:payment_method,card', 'string', 'max:255'],
            'save_card' => ['sometimes', 'boolean'],
            'billing_address' => ['nullable', 'string', 'max:500'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Order is required.',
            'order_id.exists' => 'Selected order does not exist.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'card_token.required_if' => 'Card token is required for card payments.',
            'card_holder_name.required_if' => 'Card holder name is required for card payments.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount must be at least 0.01.',
        ];
    }
}