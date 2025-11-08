<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        // one default admin for tests
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'name' => 'System Admin',
            // password: secret (factory already sets hashed secret)
        ]);
    }
}
