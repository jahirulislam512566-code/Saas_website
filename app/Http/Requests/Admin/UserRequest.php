<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'exists:roles,id'],
            'status' => ['required', 'boolean'],
        ];

        // Add password rules for creation
        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        } else {
            $rules['email'] .= ',email,' . $this->user->id;
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The user name is required.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'A password is required for new users.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.required' => 'Please select a role for the user.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Sanitize input
        $this->merge([
            'name' => strip_tags($this->name),
            'email' => strtolower(strip_tags($this->email)),
            'status' => $this->status ?? 1,
        ]);
    }
}