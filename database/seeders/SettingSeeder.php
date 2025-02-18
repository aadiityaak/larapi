<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
        $banks = [
            ['name' => 'Bank A'],
            ['name' => 'Bank B'],
            ['name' => 'Bank C'],
            ['name' => 'Bank D'],
        ];
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
            ['setting_key' => 'banks', 'setting_value' => json_encode($banks)]
        ];

        Setting::insert($templates); // Batch insert
    }
}
