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
            PostSeeder::class,
        ]);

        // Seed customers and orders
        $customers = Customer::factory(28)->create();

        foreach ($customers as $customer) {
            $ordersCount = rand(2, 4);
            $orders = Order::factory($ordersCount)->create(['customer_id' => $customer->id]);

            foreach ($orders as $order) {
                $jobdesksCount = rand(2, 5);
                Jobdesk::factory($jobdesksCount)->create([
                    'order_id' => $order->id,
                ]);
            }
        }

        // Seed notifications (after users, customers, and orders are created)
        $this->call([
            NotificationSeeder::class,
        ]);
    }
}
