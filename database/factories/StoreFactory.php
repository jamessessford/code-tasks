<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Postcode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'open' => $this->faker->boolean(75),
            'type' => $this->faker->numberBetween(1, 3),
            'max_delivery_distance' => $this->faker->numberBetween(10, 100),
        ];
    }

    public function coordinates(): Factory
    {
        return $this->state(function (array $attributes) {

            $postcode = Postcode::inRandomOrder()->limit(1)->first();

            return [
                'latitude' => $postcode->latitude,
                'longitude' => $postcode->longitude,
            ];
        });
    }
}
