<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_client()
    {
        $client = Client::factory()->create();
        $order = Order::factory()->create([
            'client_id' => $client->id,
        ]);

        $this->assertInstanceOf(Client::class, $order->client);
        $this->assertEquals($client->id, $order->client->id);
    }

    #[Test]
    public function it_belongs_to_a_user()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    #[Test]
    public function it_has_many_items()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);

        OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
        ]);

        $this->assertCount(3, $order->items);
    }
}
