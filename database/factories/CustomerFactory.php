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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'kategori' => fake()->randomElement(['Bank', 'Pribadi']),
            'pekerjaan' => fake()->jobTitle(),
            'sertifikat' => fake()->randomElement(['SHM', 'SHU', 'HIBAH']),
            'nilai_transaksi' => fake()->randomFloat(2, 1000000, 1000000000),
            'harga_real' => fake()->randomFloat(2, 1000000, 1000000000),
            'harga_kesepakatan' => fake()->randomFloat(2, 1000000, 1000000000),
            'data_pajak_pembeli' => fake()->randomFloat(2, 1000000, 1000000000),
            'data_pajak_penjual' => fake()->randomFloat(2, 1000000, 1000000000),
        ];
    }
}
