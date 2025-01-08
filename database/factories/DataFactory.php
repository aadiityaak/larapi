<?php

namespace Database\Factories;

use App\Models\Data;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFactory extends Factory
{
  protected $model = Data::class;

  public function definition()
  {
    return [
      'key' => $this->faker->word,
      'name' => $this->faker->name,
      'type' => $this->faker->word,
    ];
  }
}
