<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
  public function run()
  {
    $admin = User::factory()->create([
      'name' => 'Test Admin',
      'email' => 'user1@example.com',
      'is_admin' => true,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'password' => Hash::make('password'),
    ]);

    $manager = User::factory()->create([
      'name' => 'Test Manager',
      'email' => 'user2@example.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'password' => Hash::make('password'),
    ]);

    $finance = User::factory()->create([
      'name' => 'Test Keuangan',
      'email' => 'user3@example.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'password' => Hash::make('password'),
    ]);

    $staff1 = User::factory()->create([
      'name' => 'Test Staff',
      'email' => 'user4@example.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'password' => Hash::make('password'),
    ]);

    $staff2 = User::factory()->create([
      'name' => 'Test Staff 2',
      'email' => 'user5@example.com',
      'is_admin' => false,
      'avatar' => null,
      'phone' => '08123456789',
      'address' => 'Jl. Kebon Jeruk No. 1',
      'password' => Hash::make('password'),
    ]);

    $admin->assignRole('admin');
    $manager->assignRole('manager');
    $finance->assignRole('keuangan');
    $staff1->assignRole('staff');
    $staff2->assignRole('staff');
  }
}
