<?php

namespace Database\Factories;

use App\Models\DataProduct;
use App\Models\Data;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataProductFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = DataProduct::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'data_id' => Data::factory(), // Create or use an existing Data model
      'product_id' => Product::factory(), // Create or use an existing Product model
    ];
  }
}
