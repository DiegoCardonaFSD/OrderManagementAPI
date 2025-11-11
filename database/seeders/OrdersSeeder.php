<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $clients = Client::all();

        foreach ($clients as $client) {
            
            $user = User::where('client_id', $client->id)->first();

            if (! $user) {
                $user = User::factory()->create(['client_id' => $client->id]);
            }

            
            Order::factory()
                ->count(5)
                ->create([
                    'client_id'       => $client->id,
                    'user_id'         => $user->id,

                    'customer_name'    => $faker->name(),
                    'customer_email'   => $faker->safeEmail(),
                    'customer_phone'   => $faker->phoneNumber(),
                    'customer_address' => $faker->address(),
                    'customer_city'    => $faker->city(),
                    'customer_country' => $faker->country(),
                    'customer_tax_id'  => $faker->numerify('##########'),
                    'notes'            => $faker->optional()->sentence(),
                ])
                ->each(function (Order $order) {

                    $itemsCount = rand(1, 4);
                    $total = 0;

                    for ($i = 0; $i < $itemsCount; $i++) {
                        $item = OrderItem::factory()->make();

                        $order->items()->create([
                            'name'     => $item->name,
                            'quantity' => $item->quantity,
                            'price'    => $item->price,
                            'subtotal' => $item->subtotal,
                        ]);

                        $total += $item->subtotal;
                    }

                    $order->update([
                        'total' => $total,
                    ]);
                });
        }
    }
}
