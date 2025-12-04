<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'objectives' => [
                fake()->sentence(),
                fake()->sentence(),
                fake()->sentence(),
            ],
            'requirements' => [
                fake()->sentence(),
                fake()->sentence(),
            ],
            'instructor_id' => User::factory(),
            'category_id' => Category::factory(),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'duration' => fake()->numberBetween(1, 40),
            'price' => fake()->randomFloat(2, 0, 500),
            'discount_price' => null,
            'max_students' => fake()->numberBetween(10, 100),
            'status' => 'published',
            'is_featured' => false,
        ];
    }

    /**
     * Indicate that the course is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
            'discount_price' => null,
        ]);
    }

    /**
     * Indicate that the course is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
