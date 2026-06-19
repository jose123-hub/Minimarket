<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Full access to manage the store']);
        $cashierRole = Role::firstOrCreate(['name' => 'cashier'], ['description' => 'Operates sales and loyalty at the register']);
        $clientRole = Role::firstOrCreate(['name' => 'client'], ['description' => 'Registered customer placing orders']);

        $admin = User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id'  => $adminRole->id,
        ]);

        Employee::create([
            'name'      => 'Admin',
            'last_name' => 'Express',
            'position'  => 'Administrator',
            'dni'       => '00000001',
            'hire_date' => now(),
            'user_id'   => $admin->id,
        ]);

        $cashier = User::factory()->create([
            'name'     => 'Cashier',
            'email'    => 'cashier@example.com',
            'password' => bcrypt('password'),
            'role_id'  => $cashierRole->id,
        ]);

        Employee::create([
            'name'      => 'Cashier',
            'last_name' => 'Express',
            'position'  => 'Cashier',
            'dni'       => '00000002',
            'shift'     => 'morning',
            'hire_date' => now(),
            'user_id'   => $cashier->id,
        ]);

        $customerUser = User::factory()->create([
            'name'     => 'Client',
            'email'    => 'cliente@example.com',
            'password' => bcrypt('password'),
            'role_id'  => $clientRole->id,
        ]);

        Client::create([
            'first_name'        => 'Client',
            'last_name'         => 'Generic',
            'email'             => 'cliente@example.com',
            'accumulated_stars' => 0,
            'type'              => 'regular',
            'user_id'           => $customerUser->id,
        ]);
    }
}