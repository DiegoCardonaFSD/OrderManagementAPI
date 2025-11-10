<?php

namespace Tests\Unit\Repositories;

use App\Models\Client;
use App\Models\User;
use App\Repositories\Client\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    #[Test]
    public function it_creates_a_user()
    {
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'name'      => 'John Doe',
            'email'     => 'john@example.com',
            'password'  => 'secret',
            'role'      => 'admin',
        ];

        // COVERS LINE 18
        $user = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    #[Test]
    public function it_finds_user_by_email_and_client()
    {
        $client = Client::factory()->create();
        $otherClient = Client::factory()->create();

        $user = User::factory()->create([
            'client_id' => $client->id,
            'email'     => 'match@example.com',
        ]);

        // COVERS LINE 28
        $found = $this->repository->findByEmailAndClient('match@example.com', $client->id);

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);

        // Should NOT match wrong tenant
        $notFound = $this->repository->findByEmailAndClient('match@example.com', $otherClient->id);

        $this->assertNull($notFound);
    }

    #[Test]
    public function it_finds_user_by_email()
    {
        $user = User::factory()->create([
            'email' => 'basic@example.com',
        ]);

        $found = $this->repository->findByEmail('basic@example.com');

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);
    }
}
