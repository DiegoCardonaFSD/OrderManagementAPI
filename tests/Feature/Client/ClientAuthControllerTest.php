<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClientAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function login_returns_token_with_valid_credentials()
    {
        $client = Client::factory()->create();

        User::factory()->create([
            'client_id' => $client->id,
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret',
        ], [
            'X-Tenant-ID' => $client->id
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'user', 'token']);
    }

    #[Test]
    public function login_fails_without_tenant_header()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('tenant');
    }

    #[Test]
    public function login_fails_with_invalid_tenant()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret',
        ], [
            'X-Tenant-ID' => 9999
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('tenant');
    }

    #[Test]
    public function login_fails_with_wrong_password()
    {
        $client = Client::factory()->create();

        User::factory()->create([
            'client_id' => $client->id,
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'wrong',
        ], [
            'X-Tenant-ID' => $client->id
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email');
    }

    #[Test]
    public function login_fails_if_user_belongs_to_another_tenant()
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        User::factory()->create([
            'client_id' => $client1->id,
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret',
        ], [
            'X-Tenant-ID' => $client2->id
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email');
    }
}
