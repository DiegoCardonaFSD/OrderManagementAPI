<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'status' => 'created',
            'total' => 0,
        ];
    }
}
