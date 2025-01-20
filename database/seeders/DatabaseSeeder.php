<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jobdesk;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


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
                ]);
            }
        }

        $template_order = '
            <b>Pemberitahuan Order Layanan Notaris Baru</b><br/>
            <br/>
            Tanggal: <b>[tanggal_order]</b><br/>
            <br/>
            <b>Detail Order:</b><br/>
            ======================================<br/>
            Nama Klien: <b>[Nama Klien]</b><br/>
            Nomor Telepon: <b>[no_telp]</b><br/>
            Email: <b>[email]</b><br/>
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
            Deskripsi: <b>[deskripsi_proyek]</b><br/>
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
        Setting::create(['setting_key' => 'new_order', 'setting_value' => $template_order]);
        Setting::create(['setting_key' => 'project_assignment', 'setting_value' => $template_project]);
        Setting::create(['setting_key' => 'followup_project', 'setting_value' => $template_followup]);
        Setting::create(['setting_key' => 'app_name', 'setting_value' => 'APP']);
        Setting::create(['setting_key' => 'app_description', 'setting_value' => 'Asisten Notaris Online']);
        Setting::create(['setting_key' => 'alamat', 'setting_value' => 'Jl. Kebon Jeruk, Jakarta Timur']);
        Setting::create(['setting_key' => 'pdf_sample', 'setting_value' => 'path/to/sample.pdf']);
        Setting::create(['setting_key' => 'email', 'setting_value' => 'admin@asistennotaris.com']);
        Setting::create(['setting_key' => 'banks', 'setting_value' => 'BPR BBA, BPR Pala Pusat, BPR Pala Cabang, BPR Danamas Prime, BPR Arum Mandiri, BPRS Madina Mandiri, BMT Sejahtera Ummat']);
    }
}
