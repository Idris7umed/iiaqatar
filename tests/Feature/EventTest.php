<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_index_page_loads_successfully(): void
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
    }

    public function test_can_create_online_event(): void
    {
        $event = Event::create([
            'title' => 'Test Online Event',
            'slug' => 'test-online-event',
            'description' => 'This is a test online event',
            'event_type' => 'online',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
            'price' => 0,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Online Event',
            'event_type' => 'online',
        ]);

        $this->assertEquals('online', $event->event_type);
    }

    public function test_can_create_offline_event(): void
    {
        $event = Event::create([
            'title' => 'Test Offline Event',
            'slug' => 'test-offline-event',
            'description' => 'This is a test offline event',
            'location' => '123 Main St, City',
            'event_type' => 'offline',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
            'price' => 50.00,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Offline Event',
            'event_type' => 'offline',
            'location' => '123 Main St, City',
        ]);

        $this->assertEquals('offline', $event->event_type);
    }

    public function test_can_create_hybrid_event(): void
    {
        $event = Event::create([
            'title' => 'Test Hybrid Event',
            'slug' => 'test-hybrid-event',
            'description' => 'This is a test hybrid event',
            'location' => '123 Main St, City',
            'event_type' => 'hybrid',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
            'price' => 25.00,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Hybrid Event',
            'event_type' => 'hybrid',
        ]);

        $this->assertEquals('hybrid', $event->event_type);
    }

    public function test_can_view_published_event(): void
    {
        $event = Event::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertViewIs('events.show');
        $response->assertSee($event->title);
    }

    public function test_upcoming_scope_filters_future_events(): void
    {
        Event::factory()->create([
            'start_date' => now()->subDays(1),
            'status' => 'published',
        ]);
        Event::factory()->create([
            'start_date' => now()->addDays(7),
            'status' => 'published',
        ]);
        Event::factory()->create([
            'start_date' => now()->addDays(14),
            'status' => 'published',
        ]);

        $upcomingCount = Event::upcoming()->count();

        $this->assertEquals(2, $upcomingCount);
    }

    public function test_event_has_registrations_relationship(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'amount_paid' => $event->price,
        ]);

        $this->assertCount(1, $event->registrations);
    }

    public function test_api_returns_events_list(): void
    {
        Event::factory()->count(3)->create([
            'start_date' => now()->addDays(7),
            'status' => 'published',
        ]);

        $response = $this->getJson('/api/v1/events');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'slug', 'event_type', 'start_date'],
            ],
        ]);
    }

    public function test_api_returns_single_event(): void
    {
        $event = Event::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->getJson("/api/v1/events/{$event->slug}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title' => $event->title,
                'slug' => $event->slug,
            ],
        ]);
    }

    public function test_authenticated_user_can_register_for_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'price' => 0,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->postJson("/api/v1/events/{$event->id}/register", [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }
}
