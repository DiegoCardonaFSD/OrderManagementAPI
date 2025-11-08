<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // create 3 clients each with 3 users
        Client::factory()->count(3)->create()->each(function ($client) {
            User::factory()->count(3)->create(['client_id' => $client->id]);
        });
    }
}
