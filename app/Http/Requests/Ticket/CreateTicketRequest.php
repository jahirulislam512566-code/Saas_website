<?php

namespace App\Http\Requests\Ticket;

use App\Http\Requests\BaseRequest;

class CreateTicketRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,urgent'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'department' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Ticket subject is required.',
            'description.required' => 'Please provide a description of your issue.',
            'priority.in' => 'Invalid priority level selected.',
            'attachments.*.max' => 'Each attachment may not be larger than 5MB.',
            'attachments.*.mimes' => 'Only JPG, PNG, PDF, DOC, and DOCX files are allowed.',
        ];
    }
}