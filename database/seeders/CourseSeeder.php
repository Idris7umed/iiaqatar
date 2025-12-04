<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::where('email', 'instructor@iiaqatar.org')->first();
        $qaCategory = Category::where('name', 'Quality Assurance')->first();

        $courses = [
            [
                'title' => 'Fundamentals of Quality Assurance',
                'description' => 'Learn the core principles and practices of quality assurance in this comprehensive beginner course.',
                'objectives' => [
                    'Understand QA principles',
                    'Learn testing methodologies',
                    'Master bug tracking',
                ],
                'requirements' => [
                    'Basic computer skills',
                    'Interest in software testing',
                ],
                'level' => 'beginner',
                'duration' => 600, // 10 hours
                'price' => 199.00,
                'lessons' => [
                    ['title' => 'Introduction to QA', 'duration' => 60, 'is_free' => true],
                    ['title' => 'Testing Fundamentals', 'duration' => 90, 'is_free' => false],
                    ['title' => 'Test Case Design', 'duration' => 120, 'is_free' => false],
                    ['title' => 'Bug Reporting', 'duration' => 90, 'is_free' => false],
                ],
            ],
            [
                'title' => 'Advanced Test Automation',
                'description' => 'Master automation testing with modern tools and frameworks.',
                'objectives' => [
                    'Implement automated test suites',
                    'Use Selenium and other tools',
                    'Build CI/CD pipelines',
                ],
                'requirements' => [
                    'Basic programming knowledge',
                    'Understanding of QA basics',
                ],
                'level' => 'advanced',
                'duration' => 1200, // 20 hours
                'price' => 399.00,
                'discount_price' => 349.00,
                'lessons' => [
                    ['title' => 'Automation Overview', 'duration' => 60, 'is_free' => true],
                    ['title' => 'Selenium WebDriver', 'duration' => 180, 'is_free' => false],
                    ['title' => 'Page Object Model', 'duration' => 120, 'is_free' => false],
                    ['title' => 'CI/CD Integration', 'duration' => 150, 'is_free' => false],
                ],
            ],
            [
                'title' => 'Islamic Ethics in Quality Management',
                'description' => 'Explore how Islamic principles enhance quality management practices.',
                'objectives' => [
                    'Understand Islamic ethics',
                    'Apply principles to QA',
                    'Build ethical frameworks',
                ],
                'requirements' => [
                    'Open mind',
                    'Interest in ethics',
                ],
                'level' => 'intermediate',
                'duration' => 480, // 8 hours
                'price' => 149.00,
                'lessons' => [
                    ['title' => 'Islamic Business Ethics', 'duration' => 90, 'is_free' => true],
                    ['title' => 'Ethics in Testing', 'duration' => 120, 'is_free' => false],
                    ['title' => 'Building Trust', 'duration' => 90, 'is_free' => false],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $lessons = $courseData['lessons'];
            unset($courseData['lessons']);

            $course = Course::create([
                'title' => $courseData['title'],
                'slug' => Str::slug($courseData['title']),
                'description' => $courseData['description'],
                'objectives' => $courseData['objectives'],
                'requirements' => $courseData['requirements'],
                'instructor_id' => $instructor->id,
                'category_id' => $qaCategory->id,
                'level' => $courseData['level'],
                'duration' => $courseData['duration'],
                'price' => $courseData['price'],
                'discount_price' => $courseData['discount_price'] ?? null,
                'status' => 'published',
                'is_featured' => true,
            ]);

            // Create lessons
            foreach ($lessons as $index => $lessonData) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => $lessonData['title'],
                    'slug' => Str::slug($lessonData['title']),
                    'content' => 'Lesson content for ' . $lessonData['title'],
                    'duration' => $lessonData['duration'],
                    'order' => $index + 1,
                    'is_free' => $lessonData['is_free'],
                    'is_published' => true,
                ]);
            }
        }
    }
}
