<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'content',
        'video_url',
        'duration',
        'order',
        'is_free',
        'is_published',
    ];

    protected $casts = [
        'duration' => 'integer',
        'order' => 'integer',
        'is_free' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
