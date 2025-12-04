<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Annual Quality Assurance Conference 2024',
                'description' => 'Join us for our annual conference bringing together QA professionals from around the world.',
                'location' => 'Doha, Qatar',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(2)->addDays(2),
                'registration_deadline' => now()->addMonth(),
                'price' => 500.00,
                'max_attendees' => 200,
                'event_type' => 'offline',
            ],
            [
                'title' => 'Islamic Professional Ethics Workshop',
                'description' => 'A workshop focusing on integrating Islamic ethics in professional quality assurance practice.',
                'location' => 'Online',
                'start_date' => now()->addWeeks(3),
                'end_date' => now()->addWeeks(3)->addHours(4),
                'registration_deadline' => now()->addWeeks(2),
                'price' => 0,
                'max_attendees' => 100,
                'event_type' => 'online',
            ],
            [
                'title' => 'QA Automation Training',
                'description' => 'Hands-on training in modern QA automation tools and techniques.',
                'location' => 'IIAQATAR Training Center',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonth()->addDays(5),
                'registration_deadline' => now()->addWeeks(3),
                'price' => 1200.00,
                'max_attendees' => 30,
                'event_type' => 'hybrid',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create([
                'title' => $eventData['title'],
                'slug' => Str::slug($eventData['title']),
                'description' => $eventData['description'],
                'location' => $eventData['location'],
                'start_date' => $eventData['start_date'],
                'end_date' => $eventData['end_date'],
                'registration_deadline' => $eventData['registration_deadline'],
                'price' => $eventData['price'],
                'max_attendees' => $eventData['max_attendees'],
                'event_type' => $eventData['event_type'],
                'status' => 'published',
            ]);
        }
    }
}
