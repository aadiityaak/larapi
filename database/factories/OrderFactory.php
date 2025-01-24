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
        $price = fake()->numberBetween(1000000, 10000000);
        $paid = fake()->numberBetween(1000000, $price);
        $order_date = fake()->dateTimeBetween('-6 months', 'now');
        return [
            'customer_id' => fake()->numberBetween(1, 10),
            'order_date' => $order_date,
            'product_id' => fake()->numberBetween(1, 10),
            'price' => $price,
            'payment_method' => fake()->randomElement(['Tunai', 'Transfer']),
            'paid' => $paid,
            'meta' => null,
        ];
    }
}
