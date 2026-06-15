<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    User::factory()->create([
        'name'     => 'Admin',
        'email'    => 'admin@example.com',
        'password' => bcrypt('password'),
        'role'     => 'admin',
    ]);

    User::factory()->create([
        'name'     => 'Cajero',
        'email'    => 'cajero@example.com',
        'password' => bcrypt('password'),
        'role'     => 'cashier',
    ]);

    User::factory()->create([
        'name'     => 'Cliente',
        'email'    => 'cliente@example.com',
        'password' => bcrypt('password'),
        'role'     => 'customer',
    ]);
    }
}
