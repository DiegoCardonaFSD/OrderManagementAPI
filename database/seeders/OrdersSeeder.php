<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

/*
This specific seeder is to generate demo data
*/

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
        foreach ($clients as $client) {
            $user = User::where('client_id', $client->id)->first();
            // create 5 orders per client
            Order::factory()->count(5)->create([
                'client_id' => $client->id,
                'user_id' => $user->id,
            ])->each(function ($order) {
                // attach 1-4 items
                $itemsCount = rand(1,4);
                $total = 0;
                for ($i=0; $i<$itemsCount; $i++) {
                    $item = OrderItem::factory()->make();
                    $order->items()->create([
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ]);
                    $total += $item->subtotal;
                }
                $order->update(['total' => $total]);
            });
        }
    }
}
