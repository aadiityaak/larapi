<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobdesk>
 */
class JobdeskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['Masuk', 'Progress', 'Selesai']);
        return [
            'order_id' => fake()->numberBetween(1, 10),
            'user_id' => fake()->numberBetween(1, 10),
            'customer_id' => fake()->numberBetween(1, 10),
            'jobdesk' => fake()->randomElement(['Pengumpulan berkas', 'Pengerjaan tahap 1', 'Pengerjaan tahap 2', 'Pengerjaan tahap 3']),
            'tanggal_pengerjaan' => now()->subDays(fake()->numberBetween(5, 10)),
            'tanggal_selesai' => $status === 'Selesai' ? now() : null,
            'status' => $status
        ];
    }
}
