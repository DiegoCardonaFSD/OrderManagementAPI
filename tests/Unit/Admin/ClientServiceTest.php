<?php

namespace Tests\Unit\Admin;

use App\Models\Client;
use App\Models\User;
use App\Services\Admin\ClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClientServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ClientService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ClientService::class);
    }

    #[Test]
    public function create_client_saves_to_database()
    {
        $data = [
            'name' => 'John Doe', 
            'email' => 'john@example.com',
            'password' => 'secret987',
        ];

        $client = $this->service->createClient($data);

        $this->assertDatabaseHas('clients', ['email' => 'john@example.com']);
        $this->assertEquals('John Doe', $client->name);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'client_id' => $client->id,
            'role' => 'admin'
        ]);
    }

    #[Test]
    public function list_clients_returns_paginated_results()
    {
        Client::factory()->count(5)->create();

        $clients = $this->service->listClients([], 10);

        $this->assertEquals(5, $clients->total());
    }

    #[Test]
    public function list_clients_filters_by_name()
    {
        Client::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        Client::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);

        $filters = ['name' => 'Alice'];
        $clients = $this->service->listClients($filters);

        $this->assertCount(1, $clients);
        $this->assertEquals('Alice', $clients->first()->name);
    }

    #[Test]
    public function list_clients_filters_by_email()
    {
        Client::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        Client::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);

        $filters = ['email' => 'bob@example.com'];
        $clients = $this->service->listClients($filters);

        $this->assertCount(1, $clients);
        $this->assertEquals('bob@example.com', $clients->first()->email);
    }

    #[Test]
    public function list_clients_filters_by_name_and_email()
    {
        Client::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        Client::factory()->create(['name' => 'Alice', 'email' => 'alice2@example.com']);
        Client::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);

        $filters = ['name' => 'Alice', 'email' => 'alice2@example.com'];
        $clients = $this->service->listClients($filters);

        $this->assertCount(1, $clients);
        $this->assertEquals('alice2@example.com', $clients->first()->email);
    }

    #[Test]
    public function get_client_by_id_returns_correct_client()
    {
        $client = Client::factory()->create();

        $found = $this->service->getClientById($client->id);

        $this->assertEquals($client->id, $found->id);
    }

    #[Test]
    public function update_client_modifies_database_record()
    {
        $client = Client::factory()->create(['name' => 'Old Name']);

        $updated = $this->service->updateClient($client->id, ['name' => 'New Name']);

        $this->assertEquals('New Name', $updated->name);
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'New Name']);
    }

    #[Test]
    public function delete_client_removes_from_database()
    {
        $client = Client::factory()->create();

        $result = $this->service->deleteClient($client->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
