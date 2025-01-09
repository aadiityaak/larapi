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
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    4, // Yang Mengerjakan
                    5, // Jumlah Pinjaman
                    6, // Lain-lain
                ],
            ],
            'skmht' => [
                'title' => 'SKMHT',
                'data' => [
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    8, // Nomor Agunan
                    9, // NIB
                    10, // NOP
                    11, // Nama Pemilik Agunan
                    12, // Kode Sertifikat
                    13, // Nomor Seri
                    14, // Jumlah Pengikatan
                    7, // Tanggal Habis SKMHT
                ],
            ],
            'apht' => [
                'title' => 'APHT',
                'data' => [
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    8, // Nomor Agunan
                    9, // NIB
                    10, // NOP
                    11, // Nama Pemilik Agunan
                    12, // Kode Sertifikat
                    13, // Nomor Seri
                    14, // Jumlah Pengikatan
                ],
            ],
            'fidusia' => [
                'title' => 'Fidusia',
                'data' => [
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    15, // Jenis Agunan
                    16, // Keterangan Objek
                    17, // Bukti Kepemilikan Objek
                    18, // Nilai Objek
                    19, // Pemilik Agunan
                    20, // Nilai Penjaminan
                    21, // NPWP
                    7, // Tanggal Habis SKMHT
                ],
            ],
            'jual_beli' => [
                'title' => 'Jual Beli',
                'data' => [
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    31, // Nama Penjual
                    32, // Nama Pembeli
                    33, // Sertifikat
                    26, // Lokasi Tanah
                    34, // Nilai Jual Beli
                    35, // Nilai SSB
                    36, // Nilai SSP
                    37, // Keterangan PPJB
                    38, // Keterangan Kuasa Menjual
                    39, // Harga Real
                    40, // Harga Kesepakatan
                ],
            ],
            'hibah' => [
                'title' => 'Hibah',
                'data' => [
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    31, // Nama Penjual
                    32, // Nama Pembeli
                    33, // Sertifikat
                    26, // Lokasi Tanah
                    34, // Nilai Jual Beli
                    35, // Nilai SSB
                    36, // Nilai SSP
                    37, // Keterangan PPJB
                    38, // Keterangan Kuasa Menjual
                    39, // Harga Real
                    40, // Harga Kesepakatan
                ],
            ],
            'turun_waris' => [
                'title' => 'Turun Waris',
                'data' => [
                    22, // Berkas Masuk
                    23, // Pewaris
                    24, // Ahli Waris
                    25, // Penerima Waris
                    26, // Lokasi Tanah
                    27, // Nilai Pajak
                    28, // Berkas Kembali
                    29, // Masuk BPN
                    30, // Tanggal Akad
                ],
            ],
            'pecah' => [
                'title' => 'Pecah',
                'data' => [
                    1, // Judul Akta
                    44, // Nama Pemilik Sertifikat
                    // Tambahkan elemen lain yang sesuai jika perlu
                ],
            ],
            'pendirian_pt' => [
                'title' => 'Pendirian PT',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    42, // Nama Direktur
                    43, // Nama Komisaris
                    21, // NPWP Direktur
                    21, // NPWP Komisaris
                    44, // Nama Pemilik Manfaat
                    45, // Kedudukan PT
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'pendirian_cv' => [
                'title' => 'Pendirian CV',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    46, // Nama Persero Aktif
                    47, // Nama Persero Pasif
                    21, // NPWP Persero Aktif
                    21, // NPWP Persero Pasif
                    45, // Kedudukan PT
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'pendirian_yayasan' => [
                'title' => 'Pendirian Yayasan',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    48, // Nama Pembina
                    49, // Nama Ketua
                    50, // Nama Wakil
                    51, // Nama Bendahara
                    6, // Lain-lain
                    21, // NPWP Yayasan
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'pendirian_pt_perseorangan' => [
                'title' => 'Pendirian PT Perseorangan',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    42, // Nama Direktur
                    21, // NPWP Direktur
                    45, // Kedudukan PT
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'perubahan_pt' => [
                'title' => 'Perubahan PT',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    42, // Nama Direktur
                    43, // Nama Komisaris
                    21, // NPWP Direktur
                    21, // NPWP Komisaris
                    44, // Nama Pemilik Manfaat
                    45, // Kedudukan PT
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'perubahan_cv' => [
                'title' => 'Perubahan CV',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    46, // Nama Persero Aktif
                    47, // Nama Persero Pasif
                    21, // NPWP Persero Aktif
                    21, // NPWP Persero Pasif
                    45, // Kedudukan PT
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'perubahan_yayasan' => [
                'title' => 'Perubahan Yayasan',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    48, // Nama Pembina
                    49, // Nama Ketua
                    50, // Nama Wakil
                    51, // Nama Bendahara
                    6, // Lain-lain
                    21, // NPWP Yayasan
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
            'perubahan_pt_perseorangan' => [
                'title' => 'Perubahan PT Perseorangan',
                'data' => [
                    1, // Judul Akta
                    2, // Nomor Akta
                    3, // Tanggal Akta
                    42, // Nama Direktur
                    21, // NPWP Direktur
                    45, // Kedudukan PT
                    41, // Biaya
                    52, // Tanggal Upload
                ],
            ],
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
                'key' => $data['title'],
                'value' => $data['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert data ke tabel 'products' dan 'data_product'
        foreach ($products as $slug => $product) {
            $productId = Product::factory()->create([
                'name' => $product['title'],
                'price' => '0',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($product['data'] as $dataId) {
                DataProduct::factory()->create([
                    'data_id' => $dataId,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
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
