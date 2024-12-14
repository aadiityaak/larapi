<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            ['setting_key' => 'app_name', 'setting_value' => 'NOTANUXT'],
            ['setting_key' => 'app_description', 'setting_value' => 'NOTANUXT | Asisten Notaris Online'],
            ['setting_key' => 'alamat', 'setting_value' => 'Jl. Kebon Jeruk, Jakarta Timur'],
            ['setting_key' => 'pdf_sample', 'setting_value' => 'path/to/sample.pdf'],
            ['setting_key' => 'email', 'setting_value' => 'admin@asistennotaris.com'],
        ];
    }
}
