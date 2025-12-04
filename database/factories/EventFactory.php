<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $startDate = fake()->dateTimeBetween('+1 week', '+2 months');
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->address(),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, '+3 months'),
            'registration_deadline' => fake()->dateTimeBetween('now', $startDate),
            'max_attendees' => fake()->numberBetween(20, 200),
            'price' => fake()->randomFloat(2, 0, 300),
            'status' => 'published',
            'event_type' => fake()->randomElement(['online', 'offline', 'hybrid']),
        ];
    }

    /**
     * Indicate that the event is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
        ]);
    }

    /**
     * Indicate that the event is in the past.
     */
    public function past(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = fake()->dateTimeBetween('-2 months', '-1 week');
            
            return [
                'start_date' => $startDate,
                'end_date' => fake()->dateTimeBetween($startDate, 'now'),
                'registration_deadline' => fake()->dateTimeBetween('-3 months', $startDate),
            ];
        });
    }
}
