<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // scope validations in the middleware
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'status' => 'sometimes|in:active,inactive',
            'password'=> 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('api.admin.client.name_required'),
            'email.required' => __('api.admin.client.email_required'),
            'email.email' => __('api.admin.client.email_valid'),
            'email.unique' => __('api.admin.client.email_unique'),
            'status.in' => __('api.admin.client.status_invalid'),
            'password.required' => __('api.admin.client.password_required'),
            'password.min'      => __('api.admin.client.password_min'),
        ];
    }
}
