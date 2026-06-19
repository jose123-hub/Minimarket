<?php

namespace Database\Seeders;
use App\Models\Client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
public function run(): void
{
    $admin = User::factory()->create([
        'name'     => 'Admin',
        'email'    => 'admin@example.com',
        'password' => bcrypt('password'),
        'role'     => 'admin',
    ]);

    $cashier = User::factory()->create([
        'name'     => 'Cashier',
        'email'    => 'cashier@example.com',
        'password' => bcrypt('password'),
        'role'     => 'cashier',
    ]);

    $customerUser = User::factory()->create([
        'name'     => 'Client',
        'email'    => 'cliente@example.com',
        'password' => bcrypt('password'),
        'role'     => 'customer',
    ]);

    Client::create([
        'first_name'         => 'Client',
        'last_name'          => 'Generic',
        'email'              => 'cliente@example.com',
        'accumulated_stars'  => 0,
        'type'               => 'regular',
        'user_id'            => $customerUser->id,
    ]);
}
}
