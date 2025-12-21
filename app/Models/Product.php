<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'features',
        'featured_image',
        'category_id',
        'product_type',
        'price',
        'discount_price',
        'stock_quantity',
        'sku',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeVirtual($query)
    {
        return $query->where('product_type', 'virtual');
    }

    public function scopePhysical($query)
    {
        return $query->where('product_type', 'physical');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
