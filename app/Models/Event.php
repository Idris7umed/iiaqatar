<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'featured_image',
        'start_date',
        'end_date',
        'registration_deadline',
        'max_attendees',
        'price',
        'status',
        'event_type',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'max_attendees' => 'integer',
        'price' => 'decimal:2',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->withPivot('status', 'payment_status')
                    ->withTimestamps();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
                     ->where('status', 'published');
    }
}
