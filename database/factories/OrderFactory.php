<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $layanan_notaris = [
            'Pembuatan Akta Otentik',
            'Pembuatan Wasiat',
            'Pengesahan Dokumen',
            'Peralihan Hak Tanah',
            'Pembuatan Surat Kuasa',
            'Jasa Legalitas'
        ];
        $price = fake()->numberBetween(1000000, 10000000);
        $paid = fake()->numberBetween(1000000, $price);
        return [
            'customer_id' => fake()->numberBetween(1, 10),
            'order_date' => now(),
            'service' => fake()->randomElement($layanan_notaris),
            'price' => $price,
            'payment_method' => fake()->randomElement(['Tunai', 'Transfer']),
            'paid' => $paid,
            'document' => ['KTP', 'PBB', 'KK']
        ];
    }
}
