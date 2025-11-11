<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [

            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'nullable|email|max:255',
            'customer_phone'   => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:500',
            'customer_city'    => 'nullable|string|max:255',
            'customer_country' => 'nullable|string|max:255',
            'customer_tax_id'  => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:1000',

            'items' => 'required|array|min:1',

            'items.*.name'     => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [

            'customer_name.required' => __('api.orders.customer_name_required'),
            'customer_email.email'   => __('api.orders.customer_email_invalid'),

            'items.required' => __('api.orders.items_required'),
            'items.array'    => __('api.orders.items_array'),
            'items.min'      => __('api.orders.items_min'),

            'items.*.name.required'     => __('api.orders.item_name_required'),
            'items.*.quantity.min'      => __('api.orders.item_quantity_min'),
            'items.*.price.min'         => __('api.orders.item_price_min'),
        ];
    }
}
