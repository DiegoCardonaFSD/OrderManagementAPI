<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

use App\Models\Client;

class ClientLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'     => __('api.client.email_required'),
            'email.email'        => __('api.client.email_valid'),
            'password.required'  => __('api.client.password_required'),
            'password.min'       => __('api.client.password_min'),
        ];
    }

    protected function prepareForValidation()
    {
        $tenantId = $this->header('X-Tenant-ID');

        if (!$tenantId) {
            throw ValidationException::withMessages([
                'tenant' => __('api.client.tenant_required')
            ]);
        }

        if (!Client::find($tenantId)) {
            throw ValidationException::withMessages([
                'tenant' => __('api.client.tenant_invalid')
            ]);
        }

        // store validated tenant for controller/service
        $this->merge([
            'tenant_id' => (int) $tenantId
        ]);
    }
}
