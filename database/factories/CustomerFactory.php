<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pekerjaan = [
            'SKMHT',
            'APHT',
            'Fidusia',
            'Jual beli',
            'Hibah',
            'Turun waris',
            'Aphb',
            'Pendirian PT',
            'Pendirian CV',
            'Pendirian yayasan',
            'Pendirian PT perorangan',
            'Pendirian akta cabang',
            'Perubahan PT',
            'Perub CV',
            'Perub Yayasan',
            'Pecah sertifikat',
            'Pengeringan',
            'PBG',
            'Peningkatan Hak'
        ];
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'kategori' => fake()->randomElement(['Bank', 'Perorangan']),
            'bank' => fake()->randomElement([
                'BPR BBA',
                'BPR Pala Pusat',
                'BPR Pala Cabang',
                'BPR Danamas Prime',
                'BPR Arum Mandiri',
                'BPRS Madina Mandiri',
                'BMT Sejahtera Ummat',
            ]),
            'pekerjaan' => fake()->randomElement($pekerjaan),
            'sertifikat' => fake()->randomElement(['SHM', 'SHU', 'HIBAH']),
            'nilai_transaksi' => fake()->randomFloat(2, 1000000, 1000000000),
            'harga_real' => fake()->randomFloat(2, 1000000, 1000000000),
            'harga_kesepakatan' => fake()->randomFloat(2, 1000000, 1000000000),
            'data_pajak_pembeli' => fake()->randomFloat(2, 1000000, 1000000000),
            'data_pajak_penjual' => fake()->randomFloat(2, 1000000, 1000000000),
        ];
    }
}
