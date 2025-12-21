<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'objectives',
        'requirements',
        'featured_image',
        'instructor_id',
        'category_id',
        'level',
        'duration',
        'price',
        'discount_price',
        'max_students',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'objectives' => 'array',
        'requirements' => 'array',
        'duration' => 'integer',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'max_students' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'progress', 'completed_at')
            ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
