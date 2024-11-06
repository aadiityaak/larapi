<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Pest\ArchPresets\Custom;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 random users
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@larapi.test',
            'is_admin' => false,
            'avatar' => null,
            'phone' => '08123456789',
            'address' => 'Jl. Kebon Jeruk No. 1',
            'position' => 'Manager',
            'password' => Hash::make('password'),
        ]);

        Customer::factory(500)->create();
        Order::factory(50)->create();
    }
}
