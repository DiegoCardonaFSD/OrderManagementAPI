<?php

namespace Tests\Unit\Admin;

use App\Models\Admin;
use App\Services\Admin\AdminAuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminAuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AdminAuthService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Se usa el repositorio real que interactúa con la DB en memoria
        $this->service = app(AdminAuthService::class);
    }

    #[Test]
    public function login_returns_success_for_valid_credentials()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
        ]);

        $result = $this->service->login('admin@example.com', 'secret');

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($admin->id, $result['admin']->id);
    }

    #[Test]
    public function login_fails_for_invalid_password()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
        ]);

        $result = $this->service->login('admin@example.com', 'wrongpassword');

        $this->assertFalse($result['success']);
        $this->assertEquals(__('api.auth.invalid_credentials'), $result['message']);
    }

    #[Test]
    public function login_fails_for_non_existing_email()
    {
        $result = $this->service->login('nonexistent@example.com', 'secret');

        $this->assertFalse($result['success']);
        $this->assertEquals(__('api.auth.invalid_credentials'), $result['message']);
    }
}
