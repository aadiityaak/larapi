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
    $users = [
      [
        'email' => 'user1@example.com',
        'name' => 'Test Admin',
        'is_admin' => true,
        'role' => 'admin',
      ],
      [
        'email' => 'user2@example.com',
        'name' => 'Test Manager',
        'is_admin' => false,
        'role' => 'manager',
      ],
      [
        'email' => 'user3@example.com',
        'name' => 'Test Keuangan',
        'is_admin' => false,
        'role' => 'keuangan',
      ],
      [
        'email' => 'user4@example.com',
        'name' => 'Test Staff',
        'is_admin' => false,
        'role' => 'staff',
      ],
      [
        'email' => 'user5@example.com',
        'name' => 'Test Staff 2',
        'is_admin' => false,
        'role' => 'staff',
      ],
    ];

    foreach ($users as $userData) {
      $user = User::firstOrCreate(
        ['email' => $userData['email']],
        [
          'name' => $userData['name'],
          'is_admin' => $userData['is_admin'],
          'avatar' => null,
          'phone' => '08123456789',
          'address' => 'Jl. Kebon Jeruk No. 1',
          'password' => Hash::make('password'),
        ]
      );

      $user->assignRole($userData['role']);
    }
  }
}
