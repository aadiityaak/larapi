<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run()
  {
    User::factory()->create([
      'name' => 'Test Admin',
      'email' => 'user1@asistennotaris.com',
      'is_admin' => true,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'position' => 'Owner',
      'password' => Hash::make('password'),
    ]);
    User::factory()->create([
      'name' => 'Test Manager',
      'email' => 'user2@asistennotaris.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'position' => 'Manager',
      'password' => Hash::make('password'),
    ]);
    User::factory()->create([
      'name' => 'Test Keuangan',
      'email' => 'user3@asistennotaris.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'position' => 'Keuangan',
      'password' => Hash::make('password'),
    ]);
    User::factory()->create([
      'name' => 'Test Staff',
      'email' => 'user4@asistennotaris.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'position' => 'Staff',
      'password' => Hash::make('password'),
    ]);

    User::factory()->create([
      'name' => 'Test Staff 2',
      'email' => 'user5@asistennotaris.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'position' => 'Staff',
      'password' => Hash::make('password'),
    ]);

    // // Membuat 30 pengguna
    // User::factory(6)->create();
  }
}
