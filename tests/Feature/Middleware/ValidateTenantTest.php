<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\ValidateTenant;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ValidateTenantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake route protected by the middleware
        Route::middleware(['auth:sanctum', ValidateTenant::class])
            ->get('/middleware-test', function () {
                return response()->json(['passed' => true]);
            });
    }

    #[Test]
    public function it_blocks_request_when_tenant_header_is_missing()
    {
        $client = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('secret')
        ]);

        $token = $user->createToken('test-token', ['user.full_access'])->plainTextToken;

        $response = $this->getJson('/middleware-test', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => __('api.client.invalid_tenant'),
                 ]);
    }

    #[Test]
    public function it_blocks_request_when_tenant_does_not_match_user()
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client1->id,
            'password'  => Hash::make('secret')
        ]);

        $token = $user->createToken('test-token', ['user.full_access'])->plainTextToken;

        $response = $this->getJson('/middleware-test', [
            'Authorization' => 'Bearer ' . $token,
            'X-Tenant-ID'   => $client2->id, // mismatched tenant
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => __('api.client.invalid_tenant')
                 ]);
    }

    #[Test]
    public function it_allows_request_when_tenant_matches_user()
    {
        $client = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('secret')
        ]);

        $token = $user->createToken('test-token', ['user.full_access'])->plainTextToken;

        $response = $this->getJson('/middleware-test', [
            'Authorization' => 'Bearer ' . $token,
            'X-Tenant-ID'   => $client->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['passed' => true]);
    }
}
