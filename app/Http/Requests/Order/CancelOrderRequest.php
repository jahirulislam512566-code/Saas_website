<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;

class CancelOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'refund' => ['sometimes', 'boolean'],
            'restock' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Please provide a reason for cancellation.',
        ];
    }
}