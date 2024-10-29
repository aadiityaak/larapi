<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('customers')->insert([
                'name' => $faker->name,
                'alamat' => $faker->address,
                'whatsapp' => $faker->phoneNumber,
                'kategori' => $faker->word,
                'pekerjaan' => $faker->jobTitle,
                'bank' => $faker->company,
                'sertifikat' => $faker->word,
                'nilai_transaksi' => $faker->randomFloat(2, 1000, 100000), // random float between 1000 and 100000
                'harga_real' => $faker->randomFloat(2, 1000, 100000),
                'harga_kesepakatan' => $faker->randomFloat(2, 1000, 100000),
                'data_pajak_pembeli' => $faker->word,
                'data_pajak_penjual' => $faker->word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
