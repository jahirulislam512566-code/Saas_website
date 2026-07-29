<?php

namespace App\Http\Requests\Ticket;

use App\Http\Requests\BaseRequest;

class ReplyTicketRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:5000'],
            'is_internal' => ['sometimes', 'boolean'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
            'status' => ['nullable', 'string', 'in:open,in_progress,waiting,resolved,closed'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Reply message is required.',
            'attachments.*.max' => 'Each attachment may not be larger than 5MB.',
        ];
    }
}