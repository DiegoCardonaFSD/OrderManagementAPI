<?php

namespace Tests\Unit\Client;

use App\Models\Client;
use App\Models\User;
use App\Models\Order;
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

    private function customerPayload(): array
    {
        return [
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '+1 555 1234',
            'customer_address' => '123 Street',
            'customer_city'    => 'New York',
            'customer_country' => 'USA',
            'customer_tax_id'  => '123456789',
            'notes'            => 'Unit test order',
        ];
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

        $customerData = $this->customerPayload();

        $order = $this->service->storeOrder(
            clientId: $client->id,
            userId: $user->id,
            items: $items,
            customerData: $customerData
        );

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($client->id, $order->client_id);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(25, $order->total);
        $this->assertEquals('John Doe', $order->customer_name);        
        $this->assertCount(2, $order->items);

        $this->assertDatabaseHas('orders', [
            'id'            => $order->id,
            'client_id'     => $client->id,
            'total'         => 25,
            'customer_name' => 'John Doe',
        ]);
    }
}
