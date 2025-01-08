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
        $pekerjaan = [
            'Perjanjian Kredit' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Yang Mengerjakan' => 'string',
                'Jumlah Pinjaman' => 'decimal',
                'Lain-lain' => 'string',
            ],
            'SKMHT' => [
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nomor Agunan' => 'string',
                'NIB' => 'string',
                'NOP' => 'string',
                'Nama Pemilik Agunan' => 'string',
                'Kode Sertifikat' => 'string',
                'Nomor Seri' => 'string',
                'Jumlah Pengikatan' => 'decimal',
                'Tanggal Habis SKMHT' => 'date',
            ],
            'APHT' => [
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nomor Agunan' => 'string',
                'NIB' => 'string',
                'NOP' => 'string',
                'Nama Pemilik Agunan' => 'string',
                'Kode Sertifikat' => 'string',
                'Nomor Seri' => 'string',
                'Jumlah Pengikatan' => 'decimal',
            ],
            'Fidusia' => [
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Jenis Agunan' => 'string',
                'Keterangan Objek' => 'string',
                'Bukti Kepemilikan Objek' => 'string',
                'Nilai Objek' => 'decimal',
                'Pemilik Agunan' => 'string',
                'Nilai Penjaminan' => 'decimal',
                'NPWP' => 'string',
                'Tanggal Habis SKMHT' => 'date',
            ],
            'Jual Beli' => [
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Penjual' => 'string',
                'Nama Pembeli' => 'string',
                'Sertifikat' => 'string',
                'Lokasi Tanah' => 'string',
                'Nilai Jual Beli' => 'decimal',
                'Nilai SSB' => 'decimal',
                'Nilai SSP' => 'decimal',
                'Keterangan PPJB' => 'string',
                'Keterangan Kuasa Menjual' => 'string',
                'Harga Real' => 'decimal',
                'Harga Kesepakatan' => 'decimal',
            ],
            'Hibah' => [
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Penjual' => 'string',
                'Nama Pembeli' => 'string',
                'Sertifikat' => 'string',
                'Lokasi Tanah' => 'string',
                'Nilai Jual Beli' => 'decimal',
                'Nilai SSB' => 'decimal',
                'Nilai SSP' => 'decimal',
                'Keterangan PPJB' => 'string',
                'Keterangan Kuasa Menjual' => 'string',
                'Harga Real' => 'decimal',
                'Harga Kesepakatan' => 'decimal',
            ],
            'Turun Waris' => [
                'Berkas Masuk' => 'string',
                'Pewaris' => 'string',
                'Ahli Waris' => 'string',
                'Penerima Waris' => 'string',
                'Lokasi Tanah' => 'string',
                'Nilai Pajak' => 'decimal',
                'Berkas Kembali' => 'string',
                'Masuk BPN' => 'string',
                'Tanggal Akad' => 'date',
            ],
            'Pecah' => [
                'Tanggal Masuk Berkas' => 'date',
                'Nama Pemilik Sertifikat' => 'string',
                'Jumlah Pecah' => 'decimal',
                'Keterangan Agunan' => 'string',
                'Keterangan Berkas' => 'string',
                'Tanggal Masuk BPN' => 'date',
            ],
            'Pendirian PT' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Direktur' => 'string',
                'Nama Komisaris' => 'string',
                'NPWP Direktur' => 'string',
                'NPWP Komisaris' => 'string',
                'Nama Pemilik Manfaat' => 'string',
                'Kedudukan PT' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Pendirian CV' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Persero Aktif' => 'string',
                'Nama Persero Pasif' => 'string',
                'NPWP Persero Aktif' => 'string',
                'NPWP Persero Pasif' => 'string',
                'Kedudukan PT' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Pendirian Yayasan' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Pembina' => 'string',
                'Nama Ketua' => 'string',
                'Nama Wakil' => 'string',
                'Nama Bendahara' => 'string',
                'Lain-lain' => 'string',
                'NPWP Yayasan' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Pendirian PT Perseorangan' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Direktur' => 'string',
                'NPWP Direktur' => 'string',
                'Kedudukan PT' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Perubahan PT' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Direktur' => 'string',
                'Nama Komisaris' => 'string',
                'NPWP Direktur' => 'string',
                'NPWP Komisaris' => 'string',
                'Nama Pemilik Manfaat' => 'string',
                'Kedudukan PT' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Perubahan CV' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Persero Aktif' => 'string',
                'Nama Persero Pasif' => 'string',
                'NPWP Persero Aktif' => 'string',
                'NPWP Persero Pasif' => 'string',
                'Kedudukan PT' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Perubahan Yayasan' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Pembina' => 'string',
                'Nama Ketua' => 'string',
                'Nama Wakil' => 'string',
                'Nama Bendahara' => 'string',
                'Lain-lain' => 'string',
                'NPWP Yayasan' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
            'Perubahan PT Perseorangan' => [
                'Judul Akta' => 'string',
                'Nomor Akta' => 'string',
                'Tanggal Akta' => 'date',
                'Nama Direktur' => 'string',
                'NPWP Direktur' => 'string',
                'Kedudukan PT' => 'string',
                'Biaya' => 'decimal',
                'Keterangan' => 'string',
                'Tanggal Upload' => 'date',
            ],
        ];
        Setting::create(['setting_key' => 'app_name', 'setting_value' => 'NOTANUXT']);
        Setting::create(['setting_key' => 'app_description', 'setting_value' => 'NOTANUXT | Asisten Notaris Online']);
        Setting::create(['setting_key' => 'alamat', 'setting_value' => 'Jl. Kebon Jeruk, Jakarta Timur']);
        Setting::create(['setting_key' => 'pdf_sample', 'setting_value' => 'path/to/sample.pdf']);
        Setting::create(['setting_key' => 'email', 'setting_value' => 'admin@asistennotaris.com']);
        Setting::create(['setting_key' => 'banks', 'setting_value' => 'BPR BBA, BPR Pala Pusat, BPR Pala Cabang, BPR Danamas Prime, BPR Arum Mandiri, BPRS Madina Mandiri, BMT Sejahtera Ummat']);
        Setting::create(
            [
                'setting_key' => 'pekerjaan',
                'setting_value' => json_encode($pekerjaan)
            ]
        );
    }
}
