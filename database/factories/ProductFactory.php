<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
  protected $model = Product::class;

  public function definition()
  {
    return [
      'name' => $this->faker->word,
      'price' => $this->faker->randomFloat(0, 500000, 5000000),
      'category' => $this->faker->randomElement(['bank', 'perorangan']),
      'description' => $this->faker->paragraph,
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
