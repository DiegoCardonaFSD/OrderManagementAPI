<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminClientControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAdmin()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.full_access']);
    }

    public function test_index_returns_clients()
    {
        $this->authenticateAdmin();
        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/clients');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['data', 'current_page', 'last_page']
                 ]);
    }

    public function test_store_creates_client()
    {
        $this->authenticateAdmin();
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];

        $response = $this->postJson('/api/admin/clients', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => __('api.admin.client.created'),
                     'data' => ['name'=>'John Doe', 'email'=>'john@example.com']
                 ]);

        $this->assertDatabaseHas('clients', $data);
    }

    public function test_show_returns_client()
    {
        $this->authenticateAdmin();
        $client = Client::factory()->create();

        $response = $this->getJson("/api/admin/clients/{$client->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => ['id'=>$client->id]
                 ]);
    }

    public function test_update_modifies_client()
    {
        $this->authenticateAdmin();
        $client = Client::factory()->create();
        $updateData = ['name' => 'Jane Doe'];

        $response = $this->putJson("/api/admin/clients/{$client->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => ['name'=>'Jane Doe']
                 ]);

        $this->assertDatabaseHas('clients', ['id'=>$client->id, 'name'=>'Jane Doe']);
    }

    public function test_destroy_deletes_client()
    {
        $this->authenticateAdmin();
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/admin/clients/{$client->id}");

        $response->assertStatus(200)
                 ->assertJson(['success'=>true]);

        $this->assertDatabaseMissing('clients', ['id'=>$client->id]);
    }
}
