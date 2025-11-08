<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $qty = $this->faker->numberBetween(1, 5);
        $price = $this->faker->randomFloat(2, 1, 200);
        return [
            'order_id' => Order::factory(),
            'name' => $this->faker->word,
            'quantity' => $qty,
            'price' => $price,
            'subtotal' => round($qty * $price, 2),
        ];
    }
}
