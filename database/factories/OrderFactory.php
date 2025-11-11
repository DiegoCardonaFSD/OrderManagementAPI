<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'status' => 'created',
            'total' => 0,
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'customer_address' => $this->faker->address(),
            'customer_city' => $this->faker->city(),
            'customer_country' => $this->faker->country(),
            'customer_tax_id' => $this->faker->numerify('##########'), // e.g. NIT / Tax ID
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
