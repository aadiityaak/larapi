<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jobdesk;
use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Data;
use App\Models\DataProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $products = [
            'perjanjian_kredit' => [
                'title' => 'Perjanjian Kredit',
                'data' => [1, 2, 3, 4, 5, 6],
                'category' => 'perorangan'
            ],
            'skmht' => [
                'title' => 'SKMHT',
                'data' => [2, 3, 8, 9, 10, 11, 12, 13, 14, 7],
                'category' => 'perorangan'
            ],
            'apht' => [
                'title' => 'APHT',
                'data' => [2, 3, 8, 9, 10, 11, 12, 13, 14],
                'category' => 'perorangan'
            ],
            'fidusia' => [
                'title' => 'Fidusia',
                'data' => [2, 3, 15, 16, 17, 18, 19, 20, 21, 7],
                'category' => 'perorangan'
            ],
            'jual_beli' => [
                'title' => 'Jual Beli',
                'data' => [2, 3, 31, 32, 33, 26, 34, 35, 36, 37, 38],
                'category' => 'perorangan',
            ],
            'hibah' => [
                'title' => 'Hibah',
                'data' => [2, 3, 31, 32, 33, 26, 34, 35, 36, 37, 38],
                'category' => 'perorangan',
            ],
            'turun_waris' => [
                'title' => 'Turun Waris',
                'data' => [22, 23, 24, 25, 26, 27, 28, 29, 30],
                'category' => 'perorangan',
            ],
            'pecah' => [
                'title' => 'Pecah',
                'data' => [1, 44],
                'category' => 'perorangan',
            ],
            'pendirian_pt' => [
                'title' => 'Pendirian PT',
                'data' => [1, 2, 3, 42, 43, 21, 21, 44, 45, 41, 52],
                'category' => 'perorangan',
            ],
            'pendirian_cv' => [
                'title' => 'Pendirian CV',
                'data' => [1, 2, 3, 46, 47, 21, 21, 45, 41, 52],
                'category' => 'perorangan',
            ],
            'pendirian_yayasan' => [
                'title' => 'Pendirian Yayasan',
                'data' => [1, 2, 3, 48, 49, 50, 51, 6, 21, 41, 52],
                'category' => 'perorangan',
            ],
            'pendirian_pt_perseorangan' => [
                'title' => 'Pendirian PT Perseorangan',
                'data' => [1, 2, 3, 42, 21, 45, 41, 52],
                'category' => 'perorangan',
            ],
            'perubahan_pt' => [
                'title' => 'Perubahan PT',
                'data' => [1, 2, 3, 42, 43, 21, 21, 44, 45, 41, 52],
                'category' => 'perorangan',
            ],
            'perubahan_cv' => [
                'title' => 'Perubahan CV',
                'data' => [1, 2, 3, 46, 47, 21, 21, 45, 41, 52],
                'category' => 'perorangan',
            ],
            'perubahan_yayasan' => [
                'title' => 'Perubahan Yayasan',
                'data' => [1, 2, 3, 48, 49, 50, 51, 6, 21, 41, 52],
                'category' => 'perorangan',
            ],
            'perubahan_pt_perseorangan' => [
                'title' => 'Perubahan PT Perseorangan',
                'data' => [1, 2, 3, 42, 21, 45, 41, 52],
                'category' => 'perorangan',
            ],
            // BPR BBA, BPR Pala Pusat, BPR Pala Cabang, BPR Danamas Prime, BPR Arum Mandiri, BPRS Madina Mandiri, BMT Sejahtera Ummat'
            'bpr_bba' => [
                'title' => 'BPR BBA',
                'data' => [],
                'category' => 'bank',
            ],
            'bpr_pala_pusat' => [
                'title' => 'BPR Pala Pusat',
                'data' => [],
                'category' => 'bank',
            ],
            'bpr_pala_cabang' => [
                'title' => 'BPR Pala Cabang',
                'data' => [],
                'category' => 'bank',
            ],
            'bpr_danamas_prime' => [
                'title' => 'BPR Danamas Prime',
                'data' => [],
                'category' => 'bank',
            ],
            'bpr_arum_mandiri' => [
                'title' => 'BPR Arum Mandiri',
                'data' => [],
                'category' => 'bank',
            ],
            'bprs_madina_mandiri' => [
                'title' => 'BPRS Madina Mandiri',
                'data' => [],
                'category' => 'bank',
            ],
            'bmt_sejahtera_ummat' => [
                'title' => 'BMT Sejahtera Ummat',
                'data' => [],
                'category' => 'bank',
            ]
        ];
        $datas = [
            1 => [
                'title' => 'Judul Akta',
                'type' => 'text'
            ],
            2 => [
                'title' => 'Nomor Akta',
                'type' => 'text'
            ],
            3 => [
                'title' => 'Tanggal Akta',
                'type' => 'date'
            ],
            4 => [
                'title' => 'Yang Mengerjakan',
                'type' => 'text'
            ],
            5 => [
                'title' => 'Jumlah Pinjaman',
                'type' => 'number'
            ],
            6 => [
                'title' => 'Lain-lain',
                'type' => 'text'
            ],
            7 => [
                'title' => 'Tanggal Habis SKMHT',
                'type' => 'date'
            ],
            8 => [
                'title' => 'Nomor Agunan',
                'type' => 'text'
            ],
            9 => [
                'title' => 'NIB',
                'type' => 'text'
            ],
            10 => [
                'title' => 'NOP',
                'type' => 'text'
            ],
            11 => [
                'title' => 'Nama Pemilik Agunan',
                'type' => 'text'
            ],
            12 => [
                'title' => 'Kode Sertifikat',
                'type' => 'text'
            ],
            13 => [
                'title' => 'Nomor Seri',
                'type' => 'text'
            ],
            14 => [
                'title' => 'Jumlah Pengikatan',
                'type' => 'number'
            ],
            15 => [
                'title' => 'Jenis Agunan',
                'type' => 'text'
            ],
            16 => [
                'title' => 'Keterangan Objek',
                'type' => 'text'
            ],
            17 => [
                'title' => 'Bukti Kepemilikan Objek',
                'type' => 'text'
            ],
            18 => [
                'title' => 'Nilai Objek',
                'type' => 'number'
            ],
            19 => [
                'title' => 'Pemilik Agunan',
                'type' => 'text'
            ],
            20 => [
                'title' => 'Nilai Penjaminan',
                'type' => 'number'
            ],
            21 => [
                'title' => 'NPWP',
                'type' => 'text'
            ],
            22 => [
                'title' => 'Berkas Masuk',
                'type' => 'text'
            ],
            23 => [
                'title' => 'Pewaris',
                'type' => 'text'
            ],
            24 => [
                'title' => 'Ahli Waris',
                'type' => 'text'
            ],
            25 => [
                'title' => 'Penerima Waris',
                'type' => 'text'
            ],
            26 => [
                'title' => 'Lokasi Tanah',
                'type' => 'text'
            ],
            27 => [
                'title' => 'Nilai Pajak',
                'type' => 'number'
            ],
            28 => [
                'title' => 'Berkas Kembali',
                'type' => 'text'
            ],
            29 => [
                'title' => 'Masuk BPN',
                'type' => 'text'
            ],
            30 => [
                'title' => 'Tanggal Akad',
                'type' => 'date'
            ],
            31 => [
                'title' => 'Nama Penjual',
                'type' => 'text'
            ],
            32 => [
                'title' => 'Nama Pembeli',
                'type' => 'text'
            ],
            33 => [
                'title' => 'Sertifikat',
                'type' => 'text'
            ],
            34 => [
                'title' => 'Nilai Jual Beli',
                'type' => 'number'
            ],
            35 => [
                'title' => 'Nilai SSB',
                'type' => 'number'
            ],
            36 => [
                'title' => 'Nilai SSP',
                'type' => 'number'
            ],
            37 => [
                'title' => 'Keterangan PPJB',
                'type' => 'text'
            ],
            38 => [
                'title' => 'Keterangan Kuasa Menjual',
                'type' => 'text'
            ],
            39 => [
                'title' => 'Harga Real',
                'type' => 'number'
            ],
            40 => [
                'title' => 'Harga Kesepakatan',
                'type' => 'number'
            ],
            41 => [
                'title' => 'Biaya',
                'type' => 'number'
            ],
            42 => [
                'title' => 'Nama Direktur',
                'type' => 'text'
            ],
            43 => [
                'title' => 'Nama Komisaris',
                'type' => 'text'
            ],
            44 => [
                'title' => 'Nama Pemilik Manfaat',
                'type' => 'text'
            ],
            45 => [
                'title' => 'Kedudukan PT',
                'type' => 'text'
            ],
            46 => [
                'title' => 'Nama Persero Aktif',
                'type' => 'text'
            ],
            47 => [
                'title' => 'Nama Persero Pasif',
                'type' => 'text'
            ],
            48 => [
                'title' => 'Nama Pembina',
                'type' => 'text'
            ],
            49 => [
                'title' => 'Nama Ketua',
                'type' => 'text'
            ],
            50 => [
                'title' => 'Nama Wakil',
                'type' => 'text'
            ],
            51 => [
                'title' => 'Nama Bendahara',
                'type' => 'text'
            ],
            52 => [
                'title' => 'Tanggal Upload',
                'type' => 'date'
            ]
        ];

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

        foreach ($datas as $id => $data) {
            Data::factory()->create([
                'id' => $id,
                'name' => $data['title'],
                'type' => $data['type']
            ]);
        }

        // Insert data ke tabel 'products' dan 'data_product'
        foreach ($products as $slug => $product) {
            $productId = Product::factory()->create([
                'name' => $product['title'],
                'price' => '500000',
                'description' => '-',
                'category' => $product['category']
            ]);

            foreach ($product['data'] as $dataId) {
                DataProduct::factory()->create([
                    'data_id' => $dataId,
                    'product_id' => $productId
                ]);
            }
        }

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

        Setting::create(['setting_key' => 'app_name', 'setting_value' => 'NOTANUXT']);
        Setting::create(['setting_key' => 'app_description', 'setting_value' => 'NOTANUXT | Asisten Notaris Online']);
        Setting::create(['setting_key' => 'alamat', 'setting_value' => 'Jl. Kebon Jeruk, Jakarta Timur']);
        Setting::create(['setting_key' => 'pdf_sample', 'setting_value' => 'path/to/sample.pdf']);
        Setting::create(['setting_key' => 'email', 'setting_value' => 'admin@asistennotaris.com']);
        Setting::create(['setting_key' => 'banks', 'setting_value' => 'BPR BBA, BPR Pala Pusat, BPR Pala Cabang, BPR Danamas Prime, BPR Arum Mandiri, BPRS Madina Mandiri, BMT Sejahtera Ummat']);
    }
}
