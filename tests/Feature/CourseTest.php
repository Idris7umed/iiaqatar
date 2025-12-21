<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_index_page_loads_successfully(): void
    {
        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewIs('courses.index');
    }

    public function test_can_create_course_with_different_levels(): void
    {
        $instructor = User::factory()->create();
        $category = Category::factory()->create();

        $beginnerCourse = Course::create([
            'title' => 'Beginner Course',
            'slug' => 'beginner-course',
            'description' => 'A beginner level course',
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'level' => 'beginner',
            'duration' => 120,
            'price' => 49.99,
            'status' => 'published',
        ]);

        $advancedCourse = Course::create([
            'title' => 'Advanced Course',
            'slug' => 'advanced-course',
            'description' => 'An advanced level course',
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'level' => 'advanced',
            'duration' => 240,
            'price' => 99.99,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Beginner Course',
            'level' => 'beginner',
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Advanced Course',
            'level' => 'advanced',
        ]);
    }

    public function test_can_view_published_course(): void
    {
        $course = Course::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->get(route('courses.show', $course->slug));

        $response->assertStatus(200);
        $response->assertViewIs('courses.show');
        $response->assertSee($course->title);
    }

    public function test_published_scope_filters_correctly(): void
    {
        Course::factory()->create(['status' => 'published']);
        Course::factory()->create(['status' => 'draft']);
        Course::factory()->create(['status' => 'published']);

        $publishedCount = Course::published()->count();

        $this->assertEquals(2, $publishedCount);
    }

    public function test_course_has_instructor_relationship(): void
    {
        $instructor = User::factory()->create(['name' => 'John Doe']);
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $this->assertInstanceOf(User::class, $course->instructor);
        $this->assertEquals('John Doe', $course->instructor->name);
    }

    public function test_course_has_category_relationship(): void
    {
        $category = Category::factory()->create(['name' => 'Programming']);
        $course = Course::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $course->category);
        $this->assertEquals('Programming', $course->category->name);
    }

    public function test_course_has_lessons_relationship(): void
    {
        $course = Course::factory()->create();
        Lesson::factory()->count(3)->create(['course_id' => $course->id]);

        $this->assertCount(3, $course->lessons);
    }

    public function test_course_has_enrollments_relationship(): void
    {
        $course = Course::factory()->create();
        $user = User::factory()->create();

        Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 'active',
            'progress' => 0,
        ]);

        $this->assertCount(1, $course->enrollments);
    }

    public function test_featured_courses_can_be_filtered(): void
    {
        Course::factory()->create(['is_featured' => true, 'status' => 'published']);
        Course::factory()->create(['is_featured' => false, 'status' => 'published']);
        Course::factory()->create(['is_featured' => true, 'status' => 'published']);

        $featuredCount = Course::published()->where('is_featured', true)->count();

        $this->assertEquals(2, $featuredCount);
    }

    public function test_course_with_discount_price(): void
    {
        $course = Course::factory()->create([
            'price' => 99.99,
            'discount_price' => 79.99,
        ]);

        $this->assertEquals(99.99, $course->price);
        $this->assertEquals(79.99, $course->discount_price);
    }

    public function test_api_returns_courses_list(): void
    {
        Course::factory()->count(3)->create(['status' => 'published']);

        $response = $this->getJson('/api/v1/courses');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'slug', 'level', 'price'],
            ],
        ]);
    }

    public function test_api_returns_single_course(): void
    {
        $course = Course::factory()->create(['status' => 'published']);

        $response = $this->getJson("/api/v1/courses/{$course->slug}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title' => $course->title,
                'slug' => $course->slug,
            ],
        ]);
    }

    public function test_authenticated_user_can_enroll_in_free_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 0,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->postJson("/api/v1/courses/{$course->id}/enroll", [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('enrollments', [
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);
    }
}
