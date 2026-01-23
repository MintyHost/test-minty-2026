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
            'checkin_at' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'checkout_at' => $this->faker->dateTimeBetween('+2 days', '+2 months'),
            'status' => $this->faker->randomElement(['confirmed', 'cancelled']),
        ];
    }
}
