<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkipCashIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that SkipCash config file exists and has required keys
     */
    public function test_skipcash_config_exists(): void
    {
        $this->assertFileExists(config_path('skipcash.php'));
        
        $config = config('skipcash');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('client_id', $config);
        $this->assertArrayHasKey('key_id', $config);
        $this->assertArrayHasKey('key_secret', $config);
        $this->assertArrayHasKey('webhook_key', $config);
        $this->assertArrayHasKey('url', $config);
    }

    /**
     * Test that SkipCash routes are registered
     */
    public function test_skipcash_routes_are_registered(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('skipcash.payment.course'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('skipcash.payment.event'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('skipcash.payment.subscription'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('skipcash.payment.return'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('skipcash.payment.webhook'));
    }

    /**
     * Test course enrollment with SkipCash payment method
     */
    public function test_course_enrollment_with_skipcash(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 100.00,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->postJson(route('courses.enroll', $course), [
            'user_id' => $user->id,
            'payment_method' => 'skipcash',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Enrollment successful',
            'payment_required' => true,
            'payment_method' => 'skipcash',
        ]);
        $response->assertJsonStructure([
            'enrollment',
            'payment_url',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'unpaid',
            'payment_method' => 'skipcash',
        ]);
    }

    /**
     * Test event registration with SkipCash payment method
     */
    public function test_event_registration_with_skipcash(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'price' => 50.00,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->postJson(route('events.register', $event), [
            'user_id' => $user->id,
            'payment_method' => 'skipcash',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Registration successful',
            'payment_required' => true,
            'payment_method' => 'skipcash',
        ]);
        $response->assertJsonStructure([
            'registration',
            'payment_url',
        ]);

        $this->assertDatabaseHas('event_registrations', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'unpaid',
            'payment_method' => 'skipcash',
        ]);
    }

    /**
     * Test free course enrollment does not require payment
     */
    public function test_free_course_enrollment_no_payment(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 0,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->postJson(route('courses.enroll', $course), [
            'user_id' => $user->id,
            'payment_method' => 'skipcash',
        ]);

        $response->assertStatus(201);
        $response->assertJsonMissing(['payment_required']);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Test webhook endpoint is accessible
     */
    public function test_webhook_endpoint_is_accessible(): void
    {
        $response = $this->postJson(route('skipcash.payment.webhook'), [
            'transactionId' => 'TEST_123',
            'statusId' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Success']);
    }
}
