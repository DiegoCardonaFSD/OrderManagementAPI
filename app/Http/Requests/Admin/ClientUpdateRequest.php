<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        // scope validations in the middleware
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $this->route('id'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('api.admin.client.validation.name_required'),
            'name.string'   => __('api.admin.client.validation.name_string'),
            'name.max'      => __('api.admin.client.validation.name_max'),
            'email.required'=> __('api.admin.client.validation.email_required'),
            'email.email'   => __('api.admin.client.validation.email_email'),
            'email.unique'  => __('api.admin.client.validation.email_unique'),
            'status.in' => __('api.admin.client.status_invalid'),
        ];
    }
}
