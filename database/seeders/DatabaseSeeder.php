<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jobdesk;
use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
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
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@larapi.test',
            'is_admin' => true,
            'avatar' => null,
            'phone' => '08123456789',
            'address' => 'Jl. Kebon Jeruk No. 1',
            'position' => 'Manager',
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'name' => 'Test Manager',
            'email' => 'manager@larapi.test',
            'is_admin' => false,
            'avatar' => null,
            'phone' => '08123456789',
            'address' => 'Jl. Kebon Jeruk No. 1',
            'position' => 'Manager',
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'name' => 'Test Keuangan',
            'email' => 'keuangan@larapi.test',
            'is_admin' => false,
            'avatar' => null,
            'phone' => '08123456789',
            'address' => 'Jl. Kebon Jeruk No. 1',
            'position' => 'Keuangan',
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'name' => 'Test ',
            'email' => 'staff@larapi.test',
            'is_admin' => false,
            'avatar' => null,
            'phone' => '08123456789',
            'address' => 'Jl. Kebon Jeruk No. 1',
            'position' => 'Staff',
            'password' => Hash::make('password'),
        ]);

        // Membuat 30 pengguna
        User::factory(25)->create();

        // Membuat 5 customer
        $customers = Customer::factory(35)->create();

        foreach ($customers as $customer) {
            // Membuat antara 2 hingga 10 order untuk setiap customer
            $ordersCount = rand(2, 10);
            $orders = Order::factory($ordersCount)->create(['customer_id' => $customer->id]);

            foreach ($orders as $order) {
                // Membuat antara 2 hingga 10 jobdesk untuk setiap order
                $jobdesksCount = rand(2, 10);
                Jobdesk::factory($jobdesksCount)->create([
                    'order_id' => $order->id,
                    'customer_id' => $customer->id
                ]);
            }
        }
        // { name: 'SKMHT' },
        // { name: 'APHT'},
        // { name: 'Fidusia' },
        // { name: 'Jual beli' },
        // { name: 'Hibah' },
        // { name: 'Turun waris' },
        // { name: 'Aphb' },
        // { name: 'Pendirian PT' },
        // { name: 'Pendirian CV' },
        // { name: 'Pendirian yayasan' },
        // { name: 'Pendirian PT perorangan' },
        // { name: 'Pendirian akta cabang' },
        // { name: 'Perubahan PT' },
        // { name: 'Perub CV' },
        // { name: 'Perub Yayasan' },
        // { name: 'Pecah sertifikat' },
        // { name: 'Pengeringan' },
        // { name: 'PBG' },
        // { name: 'Peningkatan Hak' },
        Setting::create(['setting_key' => 'app_name', 'setting_value' => 'NOTANUXT']);
        Setting::create(['setting_key' => 'app_description', 'setting_value' => 'NOTANUXT | Asisten Notaris Online']);
        Setting::create(['setting_key' => 'alamat', 'setting_value' => 'Jl. Kebon Jeruk, Jakarta Timur']);
        Setting::create(['setting_key' => 'pdf_sample', 'setting_value' => 'path/to/sample.pdf']);
        Setting::create(['setting_key' => 'email', 'setting_value' => 'admin@asistennotaris.com']);
        Setting::create(['setting_key' => 'banks', 'setting_value' => 'BPR BBA, BPR Pala Pusat, BPR Pala Cabang, BPR Danamas Prime, BPR Arum Mandiri, BPRS Madina Mandiri, BMT Sejahtera Ummat']);
        Setting::create(['setting_key' => 'pekerjaan', 'setting_value' => 'SKMHT, APHT, Fidusia, Jual beli, Hibah, Turun waris, Aphb, Pendirian PT, Pendirian CV, Pendirian yayasan, Pendirian PT perorangan, Pendirian akta cabang, Perubahan PT, Perub CV, Perub Yayasan, Pecah sertifikat, Pengeringan, PBG, Peningkatan Hak']);
    }
}
