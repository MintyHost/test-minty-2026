<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'checking_in' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'checking_out' => $this->faker->dateTimeBetween('+2 days', '+2 months'),
            'status' => $this->faker->randomElement(['confirmed', 'canceled']),
        ];
    }
}
