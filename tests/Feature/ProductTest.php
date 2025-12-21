<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_page_loads_successfully(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
    }

    public function test_can_create_virtual_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::create([
            'title' => 'Test Virtual Product',
            'slug' => 'test-virtual-product',
            'description' => 'This is a test virtual product',
            'category_id' => $category->id,
            'product_type' => 'virtual',
            'price' => 99.99,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('products', [
            'title' => 'Test Virtual Product',
            'product_type' => 'virtual',
        ]);

        $this->assertEquals('virtual', $product->product_type);
    }

    public function test_can_create_physical_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::create([
            'title' => 'Test Physical Product',
            'slug' => 'test-physical-product',
            'description' => 'This is a test physical product',
            'category_id' => $category->id,
            'product_type' => 'physical',
            'price' => 49.99,
            'stock_quantity' => 100,
            'sku' => 'TEST-001',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('products', [
            'title' => 'Test Physical Product',
            'product_type' => 'physical',
            'stock_quantity' => 100,
        ]);

        $this->assertEquals('physical', $product->product_type);
    }

    public function test_can_view_published_product(): void
    {
        $product = Product::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertSee($product->title);
    }

    public function test_cannot_view_draft_product(): void
    {
        $product = Product::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(404);
    }

    public function test_published_scope_filters_correctly(): void
    {
        Product::factory()->create(['status' => 'published']);
        Product::factory()->create(['status' => 'draft']);
        Product::factory()->create(['status' => 'published']);

        $publishedCount = Product::published()->count();

        $this->assertEquals(2, $publishedCount);
    }

    public function test_virtual_scope_filters_correctly(): void
    {
        Product::factory()->create(['product_type' => 'virtual', 'status' => 'published']);
        Product::factory()->create(['product_type' => 'physical', 'status' => 'published']);
        Product::factory()->create(['product_type' => 'virtual', 'status' => 'published']);

        $virtualCount = Product::virtual()->count();

        $this->assertEquals(2, $virtualCount);
    }

    public function test_physical_scope_filters_correctly(): void
    {
        Product::factory()->create(['product_type' => 'virtual', 'status' => 'published']);
        Product::factory()->create(['product_type' => 'physical', 'status' => 'published']);
        Product::factory()->create(['product_type' => 'physical', 'status' => 'published']);

        $physicalCount = Product::physical()->count();

        $this->assertEquals(2, $physicalCount);
    }

    public function test_featured_scope_filters_correctly(): void
    {
        Product::factory()->create(['is_featured' => true, 'status' => 'published']);
        Product::factory()->create(['is_featured' => false, 'status' => 'published']);
        Product::factory()->create(['is_featured' => true, 'status' => 'published']);

        $featuredCount = Product::featured()->count();

        $this->assertEquals(2, $featuredCount);
    }

    public function test_product_has_category_relationship(): void
    {
        $category = Category::factory()->create(['name' => 'Test Category']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals('Test Category', $product->category->name);
    }

    public function test_api_returns_products_list(): void
    {
        Product::factory()->count(3)->create(['status' => 'published']);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'slug', 'price', 'product_type', 'status'],
            ],
        ]);
    }

    public function test_api_returns_single_product(): void
    {
        $product = Product::factory()->create(['status' => 'published']);

        $response = $this->getJson("/api/v1/products/{$product->slug}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title' => $product->title,
                'slug' => $product->slug,
            ],
        ]);
    }
}
