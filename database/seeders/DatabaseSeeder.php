<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jobdesk;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            SettingSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            PostSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
