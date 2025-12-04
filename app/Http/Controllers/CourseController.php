<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['instructor', 'category'])
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('courses.index', compact('courses'));
    }

    public function show($slug)
    {
        $course = Course::with(['instructor', 'category', 'lessons'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return view('courses.show', compact('course'));
    }

    public function enroll(Request $request, Course $course)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $enrollment = Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $request->user_id,
            'status' => 'active',
            'payment_status' => $course->price > 0 ? 'unpaid' : 'paid',
            'amount_paid' => $course->discount_price ?? $course->price,
        ]);

        return response()->json([
            'message' => 'Enrollment successful',
            'enrollment' => $enrollment,
        ], 201);
    }

    public function lesson($courseSlug, $lessonSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $lesson = $course->lessons()->where('slug', $lessonSlug)->firstOrFail();

        return view('courses.lesson', compact('course', 'lesson'));
    }
}
