<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);
        $productType = fake()->randomElement(['virtual', 'physical']);

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'features' => fake()->sentences(5),
            'featured_image' => fake()->imageUrl(800, 600, 'products'),
            'category_id' => Category::factory(),
            'product_type' => $productType,
            'price' => fake()->randomFloat(2, 10, 500),
            'discount_price' => fake()->optional(0.3)->randomFloat(2, 10, 400),
            'stock_quantity' => $productType === 'physical' ? fake()->numberBetween(0, 100) : null,
            'sku' => $productType === 'physical' ? strtoupper(fake()->bothify('??###')) : null,
            'status' => fake()->randomElement(['draft', 'published', 'out_of_stock']),
            'is_featured' => fake()->boolean(20),
        ];
    }

    /**
     * Indicate that the product is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the product is virtual.
     */
    public function virtual(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'virtual',
            'stock_quantity' => null,
            'sku' => null,
        ]);
    }

    /**
     * Indicate that the product is physical.
     */
    public function physical(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'physical',
            'stock_quantity' => fake()->numberBetween(0, 100),
            'sku' => strtoupper(fake()->bothify('??###')),
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
