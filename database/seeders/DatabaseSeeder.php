<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jobdesk;
use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Data;
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
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'yang_mengerjakan',
                    'jumlah_pinjaman',
                    'lain_lain',
                ],
            ],
            'skmht' => [
                'title' => 'SKMHT',
                'data' => [
                    'nomor_akta',
                    'tanggal_akta',
                    'nomor_agunan',
                    'nib',
                    'nop',
                    'nama_pemilik_agunan',
                    'kode_sertifikat',
                    'nomor_seri',
                    'jumlah_pengikatan',
                    'tanggal_habis_skmht',
                ],
            ],
            'apht' => [
                'title' => 'APHT',
                'data' => [
                    'nomor_akta',
                    'tanggal_akta',
                    'nomor_agunan',
                    'nib',
                    'nop',
                    'nama_pemilik_agunan',
                    'kode_sertifikat',
                    'nomor_seri',
                    'jumlah_pengikatan',
                ],
            ],
            'fidusia' => [
                'title' => 'Fidusia',
                'data' => [
                    'nomor_akta',
                    'tanggal_akta',
                    'jenis_agunan',
                    'keterangan_objek',
                    'bukti_kepemilikan_objek',
                    'nilai_objek',
                    'pemilik_agunan',
                    'nilai_penjaminan',
                    'npwp',
                    'tanggal_habis_skmht',
                ],
            ],
            'jual_beli' => [
                'title' => 'Jual Beli',
                'data' => [
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_penjual',
                    'nama_pembeli',
                    'sertifikat',
                    'lokasi_tanah',
                    'nilai_jual_beli',
                    'nilai_ssb',
                    'nilai_ssp',
                    'keterangan_ppjb',
                    'keterangan_kuasa_menjual',
                    'harga_real',
                    'harga_kesepakatan',
                ],
            ],
            'hibah' => [
                'title' => 'Hibah',
                'data' => [
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_penjual',
                    'nama_pembeli',
                    'sertifikat',
                    'lokasi_tanah',
                    'nilai_jual_beli',
                    'nilai_ssb',
                    'nilai_ssp',
                    'keterangan_ppjb',
                    'keterangan_kuasa_menjual',
                    'harga_real',
                    'harga_kesepakatan',
                ],
            ],
            'turun_waris' => [
                'title' => 'Turun Waris',
                'data' => [
                    'berkas_masuk',
                    'pewaris',
                    'ahli_waris',
                    'penerima_waris',
                    'lokasi_tanah',
                    'nilai_pajak',
                    'berkas_kembali',
                    'masuk_bpn',
                    'tanggal_akad',
                ],
            ],
            'pecah' => [
                'title' => 'Pecah',
                'data' => [
                    'tanggal_masuk_berkas',
                    'nama_pemilik_sertifikat',
                    'jumlah_pecah',
                    'keterangan_agunan',
                    'keterangan_berkas',
                    'tanggal_masuk_bpn',
                ],
            ],
            'pendirian_pt' => [
                'title' => 'Pendirian PT',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_direktur',
                    'nama_komisaris',
                    'npwp_direktur',
                    'npwp_komisaris',
                    'nama_pemilik_manfaat',
                    'kedudukan_pt',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'pendirian_cv' => [
                'title' => 'Pendirian CV',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_persero_aktif',
                    'nama_persero_pasib',
                    'npwp_persero_aktif',
                    'npwp_persero_pasib',
                    'kedudukan_pt',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'pendirian_yayasan' => [
                'title' => 'Pendirian Yayasan',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_pembina',
                    'nama_ketua',
                    'nama_wakil',
                    'nama_bendahara',
                    'lain_lain',
                    'npwp_yayasan',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'pendirian_pt_perseorangan' => [
                'title' => 'Pendirian PT Perseorangan',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_direktur',
                    'npwp_direktur',
                    'kedudukan_pt',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'perubahan_pt' => [
                'title' => 'Perubahan PT',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_direktur',
                    'nama_komisaris',
                    'npwp_direktur',
                    'npwp_komisaris',
                    'nama_pemilik_manfaat',
                    'kedudukan_pt',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'perubahan_cv' => [
                'title' => 'Perubahan CV',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_persero_aktif',
                    'nama_persero_pasib',
                    'npwp_persero_aktif',
                    'npwp_persero_pasib',
                    'kedudukan_pt',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'perubahan_yayasan' => [
                'title' => 'Perubahan Yayasan',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_pembina',
                    'nama_ketua',
                    'nama_wakil',
                    'nama_bendahara',
                    'lain_lain',
                    'npwp_yayasan',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
            'perubahan_pt_perseorangan' => [
                'title' => 'Perubahan PT Perseorangan',
                'data' => [
                    'judul_akta',
                    'nomor_akta',
                    'tanggal_akta',
                    'nama_direktur',
                    'npwp_direktur',
                    'kedudukan_pt',
                    'biaya',
                    'keterangan',
                    'tanggal_upload',
                ],
            ],
        ];
        $datas = [
            'judul_akta' => [
                'title' => 'Judul Akta',
                'type' => 'text'
            ],
            'nomor_akta' => [
                'title' => 'Nomor Akta',
                'type' => 'text'
            ],
            'tanggal_akta' => [
                'title' => 'Tanggal Akta',
                'type' => 'date'
            ],
            'yang_mengerjakan' => [
                'title' => 'Yang Mengerjakan',
                'type' => 'text'
            ],
            'jumlah_pinjaman' => [
                'title' => 'Jumlah Pinjaman',
                'type' => 'number'
            ],
            'lain_lain' => [
                'title' => 'Lain-lain',
                'type' => 'text'
            ],
            'tanggal_habis_skmht' => [
                'title' => 'Tanggal Habis SKMHT',
                'type' => 'date'
            ],
            'nomor_agunan' => [
                'title' => 'Nomor Agunan',
                'type' => 'text'
            ],
            'nib' => [
                'title' => 'NIB',
                'type' => 'text'
            ],
            'nop' => [
                'title' => 'NOP',
                'type' => 'text'
            ],
            'nama_pemilik_agunan' => [
                'title' => 'Nama Pemilik Agunan',
                'type' => 'text'
            ],
            'kode_sertifikat' => [
                'title' => 'Kode Sertifikat',
                'type' => 'text'
            ],
            'nomor_seri' => [
                'title' => 'Nomor Seri',
                'type' => 'text'
            ],
            'jumlah_pengikatan' => [
                'title' => 'Jumlah Pengikatan',
                'type' => 'number'
            ],
            'jenis_agunan' => [
                'title' => 'Jenis Agunan',
                'type' => 'text'
            ],
            'keterangan_objek' => [
                'title' => 'Keterangan Objek',
                'type' => 'text'
            ],
            'bukti_kepemilikan_objek' => [
                'title' => 'Bukti Kepemilikan Objek',
                'type' => 'text'
            ],
            'nilai_objek' => [
                'title' => 'Nilai Objek',
                'type' => 'number'
            ],
            'pemilik_agunan' => [
                'title' => 'Pemilik Agunan',
                'type' => 'text'
            ],
            'nilai_penjaminan' => [
                'title' => 'Nilai Penjaminan',
                'type' => 'number'
            ],
            'npwp' => [
                'title' => 'NPWP',
                'type' => 'text'
            ],
            'berkas_masuk' => [
                'title' => 'Berkas Masuk',
                'type' => 'text'
            ],
            'pewaris' => [
                'title' => 'Pewaris',
                'type' => 'text'
            ],
            'ahli_waris' => [
                'title' => 'Ahli Waris',
                'type' => 'text'
            ],
            'penerima_waris' => [
                'title' => 'Penerima Waris',
                'type' => 'text'
            ],
            'lokasi_tanah' => [
                'title' => 'Lokasi Tanah',
                'type' => 'text'
            ],
            'nilai_pajak' => [
                'title' => 'Nilai Pajak',
                'type' => 'number'
            ],
            'berkas_kembali' => [
                'title' => 'Berkas Kembali',
                'type' => 'text'
            ],
            'masuk_bpn' => [
                'title' => 'Masuk BPN',
                'type' => 'text'
            ],
            'tanggal_akad' => [
                'title' => 'Tanggal Akad',
                'type' => 'date'
            ],
            'nama_penjual' => [
                'title' => 'Nama Penjual',
                'type' => 'text'
            ],
            'nama_pembeli' => [
                'title' => 'Nama Pembeli',
                'type' => 'text'
            ],
            'sertifikat' => [
                'title' => 'Sertifikat',
                'type' => 'text'
            ],
            'nilai_jual_beli' => [
                'title' => 'Nilai Jual Beli',
                'type' => 'number'
            ],
            'nilai_ssb' => [
                'title' => 'Nilai SSB',
                'type' => 'number'
            ],
            'nilai_ssp' => [
                'title' => 'Nilai SSP',
                'type' => 'number'
            ],
            'keterangan_ppjb' => [
                'title' => 'Keterangan PPJB',
                'type' => 'text'
            ],
            'keterangan_kuasa_menjual' => [
                'title' => 'Keterangan Kuasa Menjual',
                'type' => 'text'
            ],
            'harga_real' => [
                'title' => 'Harga Real',
                'type' => 'number'
            ],
            'harga_kesepakatan' => [
                'title' => 'Harga Kesepakatan',
                'type' => 'number'
            ],
            'biaya' => [
                'title' => 'Biaya',
                'type' => 'number'
            ],
            'nama_direktur' => [
                'title' => 'Nama Direktur',
                'type' => 'text'
            ],
            'nama_komisaris' => [
                'title' => 'Nama Komisaris',
                'type' => 'text'
            ],
            'nama_pemilik_manfaat' => [
                'title' => 'Nama Pemilik Manfaat',
                'type' => 'text'
            ],
            'kedudukan_pt' => [
                'title' => 'Kedudukan PT',
                'type' => 'text'
            ],
            'nama_persero_aktif' => [
                'title' => 'Nama Persero Aktif',
                'type' => 'text'
            ],
            'nama_persero_pasib' => [
                'title' => 'Nama Persero Pasif',
                'type' => 'text'
            ],
            'nama_pembina' => [
                'title' => 'Nama Pembina',
                'type' => 'text'
            ],
            'nama_ketua' => [
                'title' => 'Nama Ketua',
                'type' => 'text'
            ],
            'nama_wakil' => [
                'title' => 'Nama Wakil',
                'type' => 'text'
            ],
            'nama_bendahara' => [
                'title' => 'Nama Bendahara',
                'type' => 'text'
            ],
            'tanggal_upload' => [
                'title' => 'Tanggal Upload',
                'type' => 'date'
            ],
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

        foreach ($products as $key => $product) {
            Product::factory()->create([
                'name' => $product['title'],
                'price' => rand(100000, 5000000),
                'description' => '',
                'data' => implode(',', $product['data']),
            ]);
        }

        foreach ($datas as $key => $val) {
            Data::factory()->create([
                'key' => $key,
                'value' => $val['title'],
                'type' => $val['type']
            ]);
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
