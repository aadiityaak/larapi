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
        $status = random_int(1, 100) <= 80 ? 'Selesai' : fake()->randomElement(['Masuk', 'Progress']);

        return [
            'order_id' => fake()->numberBetween(1, 10),
            'deskripsi' => fake()->sentence(),
            'user_id' => $status !== 'Masuk' ? fake()->numberBetween(1, 10) : null,
            'tanggal_pengerjaan' => $status !== 'Masuk' ? now()->subDays(fake()->numberBetween(5, 10)) : null,
            'tanggal_selesai' => $status === 'Selesai' ? now() : null,
            'status' => $status
        ];
    }
}
