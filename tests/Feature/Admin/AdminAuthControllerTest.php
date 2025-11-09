<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function login_endpoint_returns_token_for_valid_credentials()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret')
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'message',
                'admin' => ['id','name','email']
            ]);
    }

    #[Test]
    public function login_endpoint_returns_401_for_invalid_password()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret')
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => __('api.auth.invalid_credentials')]);
    }

    #[Test]
    public function login_endpoint_returns_401_for_non_existing_email()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => __('api.auth.invalid_credentials')]);
    }
}
