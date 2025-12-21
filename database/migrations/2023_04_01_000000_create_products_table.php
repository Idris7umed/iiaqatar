<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->text('features')->nullable();
            $table->string('featured_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('product_type', ['virtual', 'physical'])->default('physical');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->string('sku')->nullable();
            $table->enum('status', ['draft', 'published', 'out_of_stock'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
