<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // tenant + sanctum handled in middleware
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',

            'items.*.name'     => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => __('api.orders.items_required'),
            'items.array'    => __('api.orders.items_array'),
            'items.min'      => __('api.orders.items_min'),

            'items.*.name.required'     => __('api.orders.item_name_required'),
            'items.*.quantity.min'      => __('api.orders.item_quantity_min'),
            'items.*.price.min'         => __('api.orders.item_price_min'),
        ];
    }
}
