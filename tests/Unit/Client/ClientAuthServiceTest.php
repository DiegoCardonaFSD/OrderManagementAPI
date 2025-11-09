<?php

namespace Tests\Unit\Client;

use App\Models\Client;
use App\Models\User;
use App\Services\Client\ClientAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClientAuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ClientAuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ClientAuthService::class);
    }

    #[Test]
    public function login_returns_success_for_valid_credentials()
    {
        $client = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client->id,
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
        ]);

        $result = $this->service->login(
            ['email' => 'user@example.com', 'password' => 'secret'],
            $client->id
        );

        $this->assertTrue($result['success']);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertArrayHasKey('token', $result);
    }

    #[Test]
    public function login_fails_for_invalid_password()
    {
        $client = Client::factory()->create();

        User::factory()->create([
            'client_id' => $client->id,
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->service->login(
            ['email' => 'user@example.com', 'password' => 'wrong'],
            $client->id
        );
    }

    #[Test]
    public function login_fails_for_non_existing_user()
    {
        $client = Client::factory()->create();

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->service->login(
            ['email' => 'missing@example.com', 'password' => 'secret'],
            $client->id
        );
    }

    #[Test]
    public function login_fails_if_user_does_not_belong_to_tenant()
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        User::factory()->create([
            'client_id' => $client1->id,
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->service->login(
            ['email' => 'user@example.com', 'password' => 'secret'],
            $client2->id
        );
    }
}
