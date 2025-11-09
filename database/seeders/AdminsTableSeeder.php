<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        // one default admin for tests
        /*Admin::factory()->create([
            'email' => 'diego@example.com',
            'name' => 'Diego Cardona Admin',
            'password' => Hash::make('secret'),
        ]);*/
        $admin = Admin::updateOrCreate(
            ['email' => 'diego@example.com'],
            [
                'name' => 'Diego Cardona Admin',
                'password' => Hash::make('secret'),
            ]
        );

        //$admin->tokens()->delete();
        //$admin->createToken('Admin Token', ['admin.full_access']);
    }
}
