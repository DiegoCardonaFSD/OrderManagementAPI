<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\CheckScopes;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckScopesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['api', 'auth:sanctum', CheckScopes::class . ':client.full_access'])
            ->any('/test-scope', function () {
                return response()->json(['ok' => true], 200);
            });
    }

    #[Test]
    public function it_returns_401_when_user_is_missing()
    {
        $response = $this->json('GET', '/test-scope');

        $response->assertStatus(401);
        $response->assertJson(['message' => __('api.auth.unauthorized')]);
    }

    #[Test]
    public function it_returns_403_when_user_lacks_required_scope()
    {
        $client = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('test12345'),
        ]);

        $token = $user->createToken('test-token', ['some.other.scope'])->plainTextToken;

        $response = $this->json('GET', '/test-scope', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => __("api.auth.forbidden_scope", ['scope' => 'client.full_access'])
        ]);
    }

    #[Test]
    public function it_allows_request_when_user_has_required_scope()
    {
        $client = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('test12345'),
        ]);

        $token = $user->createToken('test-token', ['client.full_access'])->plainTextToken;

        $response = $this->json('GET', '/test-scope', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
    }

    #[Test]
    public function it_returns_401_inside_checkscopes_when_no_user_is_present()
    {
        // Register a route with ONLY CheckScopes
        Route::any('/test-scope-direct', function () {
            return response()->json(['ok' => true]);
        })->middleware(CheckScopes::class . ':client.full_access');

        // Disable global API middlewares for this request
        $this->withoutMiddleware('auth:sanctum');
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\SubstituteBindings::class);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

        // Call endpoint WITHOUT user or token
        $response = $this->json('GET', '/test-scope-direct');

        $response->assertStatus(401);
        $response->assertJson([
            'message' => __('api.auth.unauthorized')
        ]);
    }

}
