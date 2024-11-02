<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Customer::create([
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'kategori' => $faker->word,
                'pekerjaan' => $faker->jobTitle,
                'sertifikat' => $faker->word,
                'nilai_transaksi' => $faker->randomFloat(2, 1000, 10000),
                'harga_real' => $faker->randomFloat(2, 500, 5000),
                'harga_kesepakatan' => $faker->randomFloat(2, 500, 5000),
                'data_pajak_pembeli' => $faker->randomFloat(2, 0, 1000),
                'data_pajak_penjual' => $faker->randomFloat(2, 0, 1000),
            ]);
        }
    }
}
