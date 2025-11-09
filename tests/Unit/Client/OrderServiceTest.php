<?php

namespace Tests\Unit\Client;

use App\Models\Client;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Client\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrderService::class);
    }

    #[Test]
    public function it_creates_an_order_with_items_and_calculates_total()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('secret'),
        ]);

        $items = [
            ['name' => 'Item A', 'quantity' => 2, 'price' => 10],
            ['name' => 'Item B', 'quantity' => 1, 'price' => 5],
        ];

        $order = $this->service->storeOrder($client->id, $user->id, $items);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($client->id, $order->client_id);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(25, $order->total);

        $this->assertCount(2, $order->items);
        $this->assertDatabaseHas('orders', [
            'id'        => $order->id,
            'client_id' => $client->id,
            'total'     => 25,
        ]);
    }
}
