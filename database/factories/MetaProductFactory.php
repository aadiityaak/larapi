<?php

namespace Database\Factories;

use App\Models\MetaProduct;
use App\Models\Meta;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetaProductFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = MetaProduct::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'meta_id' => Meta::factory(),
      'product_id' => Product::factory(),
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
