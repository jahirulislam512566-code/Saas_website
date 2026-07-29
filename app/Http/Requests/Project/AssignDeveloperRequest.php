<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class AssignDeveloperRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'developer_id' => ['required', 'exists:users,id'],
            'role' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'notify_developer' => ['sometimes', 'boolean'],
            'check_skills' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'developer_id.required' => 'Developer is required.',
            'developer_id.exists' => 'Selected developer does not exist.',
        ];
    }
}