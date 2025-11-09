<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
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

    #[Test]
    public function it_creates_an_order_successfully()
    {
        $client = Client::factory()->create();
        [$user, $token] = $this->createAuthenticatedTenantUser($client);

        $payload = [
            "items" => [
                ["name" => "Item A", "quantity" => 2, "price" => 10],
                ["name" => "Item B", "quantity" => 1, "price" => 5]
            ]
        ];

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
            'client_id' => $client->id,
            'user_id'   => $user->id,
            'total'     => 25
        ]);
    }

    #[Test]
    public function it_fails_when_items_are_missing()
    {
        $client = Client::factory()->create();
        [$user, $token] = $this->createAuthenticatedTenantUser($client);

        $response = $this->postJson('/api/orders', [], [
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

        $payload = [
            "items" => [
                ["name" => "", "quantity" => 0, "price" => -1]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload, [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id
        ]);

        $response->assertStatus(422)
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

        $payload = [
            "items" => [
                ["name" => "Item A", "quantity" => 1, "price" => 10]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload, [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client2->id
        ]);

        $response->assertStatus(403);
    }
}
