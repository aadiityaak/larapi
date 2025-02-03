<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jobdesk;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
        ]);
        // Seed settings first
        $this->seedSettings();
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
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
    }

    protected function seedSettings()
    {
        $template_order = '
            <b>Pemberitahuan Order Layanan Notaris Baru</b><br/>
            <br/>
            Tanggal: <b>[tanggal_order]</b><br/>
            <br/>
            <b>Detail Order:</b><br/>
            ======================================<br/>
            Nama Klien: <b>[nama_klien]</b><br/>
            Nomor Telepon: <b>[no_telp]</b><br/>
            Jenis Layanan Notaris: <b>[product]</b><br/>
            ======================================<br/>
            <br/>
            Harap segera follow up order ini ke staff terkait.<br/>
            <br/>
            <b>Terima kasih.</b><br/>
            [tim_manajemen]
        ';
        $template_project = '
            <b>Pemberitahuan Penugasan Proyek</b><br/>
            <br/>
            Tanggal: <b>[tanggal_penugasan]</b><br/>
            <br/>
            <b>Detail Proyek:</b><br/>
            ======================================<br/>
            Nama Proyek: <b>[nama_proyek]</b><br/>
            Deskripsi: <b>[description_proyek]</b><br/>
            Batas Waktu: <b>[batas_waktu]</b><br/>
            Penanggung Jawab: <b>[nama_superadmin]</b><br/>
            ======================================<br/>
            <br/>
            Harap segera mulai bekerja pada proyek ini dan laporkan perkembangan secara berkala.<br/>
            <br/>
            <b>Terima kasih.</b><br/>
            [tim_manajemen]
        ';
        $template_followup = '
            <b>Follow-Up Proyek</b><br/>
            <br/>
            Tanggal: <b>[tanggal_followup]</b><br/>
            <br/>
            <b>Detail Proyek:</b><br/>
            ======================================<br/>
            Nama Proyek: <b>[nama_proyek]</b><br/>
            Penanggung Jawab: <b>[nama_karyawan]</b><br/>
            Batas Waktu: <b>[batas_waktu]</b><br/>
            Status Terakhir: <b>[status_terakhir]</b><br/>
            ======================================<br/>
            <br/>
            Harap berikan update mengenai perkembangan proyek ini dan jika ada kendala, silakan laporkan segera.<br/>
            <br/>
            <b>Terima kasih.</b><br/>
            [tim_manajemen]
        ';
        $templates = [
            ['setting_key' => 'new_order', 'setting_value' => $template_order],
            ['setting_key' => 'project_assignment', 'setting_value' => $template_project],
            ['setting_key' => 'followup_project', 'setting_value' => $template_followup],

            ['setting_key' => 'app_name', 'setting_value' => 'APP'],
            ['setting_key' => 'app_code', 'setting_value' => 'AN'],
            ['setting_key' => 'app_description', 'setting_value' => 'Asisten Notaris Online'],
            ['setting_key' => 'address', 'setting_value' => 'Jl. Kebon Jeruk, Jakarta Timur'],
            ['setting_key' => 'pdf_sample', 'setting_value' => 'path/to/sample.pdf'],
            ['setting_key' => 'email', 'setting_value' => 'admin@asistennotaris.com'],
        ];

        Setting::insert($templates); // Batch insert
    }
}
