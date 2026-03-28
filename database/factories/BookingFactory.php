<?php

namespace Database\Factories;

use App\Enums\ServiceType;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
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
            'service' => fake()->randomElement(ServiceType::cases()),
            'date' => now()->addDays(fake()->unique()->numberBetween(1, 90))->format('Y-m-d'),
        ];
    }
}
