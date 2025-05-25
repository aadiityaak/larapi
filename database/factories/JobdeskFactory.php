<?php

namespace Database\Factories;

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
    $staffs = User::role('staff')->get();
    return [
      'order_id' => fake()->numberBetween(1, 10),
      'description' => fake()->sentence(),
      'user_id' => $staffs->random()->id,
      'tanggal_pengerjaan' => $status !== 'Masuk' ? now()->subDays(fake()->numberBetween(5, 10)) : null,
      'tanggal_selesai' => $status === 'Selesai' ? now() : null,
      'status' => $status
    ];
  }
}
