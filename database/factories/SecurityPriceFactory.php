<?php

namespace Database\Factories;

use App\Models\Security;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecurityPrice>
 */
class SecurityPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_price' => fake()->randomFloat(2,1,1000),
            'security_id' => Security::factory(),
            'as_of_date' => fake()->dateTime
        ];
    }
}
