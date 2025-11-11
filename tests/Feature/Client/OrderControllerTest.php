<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedTenantUser(Client $client): array
    {
        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('secret'),
        ]);

        $token = $user->createToken('test-token', ['client.full_access'])->plainTextToken;

        return [$user, $token];
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
            'notes'            => 'Test note',
        ];
    }

    #[Test]
    public function it_creates_an_order_successfully()
    {
        $client = Client::factory()->create();
        [$user, $token] = $this->createAuthenticatedTenantUser($client);

        $payload = array_merge($this->customerPayload(), [
            "items" => [
                ["name" => "Item A", "quantity" => 2, "price" => 10],
                ["name" => "Item B", "quantity" => 1, "price" => 5]
            ]
        ]);

        $response = $this->postJson('/api/orders', $payload, [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id,
            'Accept'        => 'application/json'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => __('api.orders.created')
            ]);

        $this->assertDatabaseHas('orders', [
            'client_id'     => $client->id,
            'user_id'       => $user->id,
            'total'         => 25,
            'customer_name' => 'John Doe',
        ]);
    }

    #[Test]
    public function it_fails_when_items_are_missing()
    {
        $client = Client::factory()->create();
        [$user, $token] = $this->createAuthenticatedTenantUser($client);

        $response = $this->postJson('/api/orders', $this->customerPayload(), [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('items');
    }

    #[Test]
    public function it_fails_when_item_fields_are_invalid()
    {
        $client = Client::factory()->create();
        [$user, $token] = $this->createAuthenticatedTenantUser($client);

        $payload = array_merge($this->customerPayload(), [
            "items" => [
                ["name" => "", "quantity" => 0, "price" => -1]
            ]
        ]);

        $response = $this->postJson('/api/orders', $payload, [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'items.0.name',
                'items.0.quantity',
                'items.0.price',
            ]);
    }

    #[Test]
    public function it_denies_access_when_tenant_header_is_incorrect()
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        [, $token] = $this->createAuthenticatedTenantUser($client1);

        $payload = array_merge($this->customerPayload(), [
            "items" => [
                ["name" => "Item A", "quantity" => 1, "price" => 10]
            ]
        ]);

        $response = $this->postJson('/api/orders', $payload, [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client2->id
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function it_returns_order_for_authenticated_user()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);

        OrderItem::factory()->count(2)->create(['order_id' => $order->id]);

        $token = $user->createToken('user-token', ['client.full_access'])->plainTextToken;

        $response = $this->json('GET', "/api/orders/{$order->id}", [], [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'items' => [
                        ['id', 'name', 'quantity', 'price', 'subtotal']
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_if_order_does_not_belong_to_user()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $token = $user->createToken('user-token', ['client.full_access'])->plainTextToken;

        $response = $this->json('GET', "/api/orders/{$order->id}", [], [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id,
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function client_user_can_list_its_own_orders()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id'   => $user->id,
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id
        ]);

        $token = $user->createToken('user-token', ['client.full_access'])->plainTextToken;

        $response = $this->json('GET', "/api/clients/{$client->id}/orders", [], [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id,
            'X-API-Version' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    [
                        'id',
                        'client_id',
                        'user_id',
                        'status',
                        'total',
                        'items' => [
                            ['id', 'order_id', 'name', 'quantity', 'price', 'subtotal']
                        ]
                    ]
                ]
            ]);
    }

    #[Test]
    public function cannot_list_orders_of_other_tenant()
    {
        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();

        $user = User::factory()->create(['client_id' => $clientA->id]);

        $token = $user->createToken('user-token', ['client.full_access'])->plainTextToken;

        $response = $this->json('GET', "/api/clients/{$clientB->id}/orders", [], [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $clientA->id,
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => __('api.client.invalid_tenant')
            ]);
    }
}
