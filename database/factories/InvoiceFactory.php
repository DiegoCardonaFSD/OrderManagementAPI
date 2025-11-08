<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'status' => 'created',
            'message' => $this->faker->sentence,
        ];
    }
}
