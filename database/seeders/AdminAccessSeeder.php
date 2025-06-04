<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminAccessSeeder extends Seeder
{
  public function run()
  {
    // Email & password tetap
    $email = 'superadmin@example.com';
    $password = 'admin123'; // Ubah jadi password kamu

    // Cek apakah sudah ada
    $admin = User::where('email', $email)->first();

    if (!$admin) {
      $admin = User::create([
        'name' => 'Super Admin',
        'email' => $email,
        'is_admin' => true,
        'avatar' => null,
        'phone' => '081111111111',
        'address' => 'Admin Default Access',
        'password' => Hash::make($password),
      ]);

      $admin->assignRole('admin');
      echo "Admin user created: $email / $password\n";
    } else {
      // Reset password jika user sudah ada
      $admin->password = Hash::make($password);
      $admin->save();
      echo "Admin user password reset: $email / $password\n";
    }
  }
}
