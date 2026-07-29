<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class CreateProjectRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'project_type' => ['required', 'string', 'in:website,logo,seo,mobile_app,consulting'],
            'customer_id' => ['required', 'exists:customers,id'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,urgent'],
            'assigned_team' => ['nullable', 'array'],
            'assigned_team.*' => ['exists:users,id'],
            'milestones' => ['nullable', 'array'],
            'milestones.*.title' => ['required', 'string', 'max:255'],
            'milestones.*.description' => ['nullable', 'string', 'max:1000'],
            'milestones.*.due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:100'],
            'requirements' => ['nullable', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'], // 10MB max
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'project_type.required' => 'Project type is required.',
            'project_type.in' => 'Invalid project type selected.',
            'customer_id.required' => 'Customer is required.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'end_date.after' => 'End date must be after start date.',
            'budget.numeric' => 'Budget must be a number.',
            'budget.min' => 'Budget cannot be negative.',
            'attachments.*.max' => 'Each attachment may not be larger than 10MB.',
        ];
    }
}