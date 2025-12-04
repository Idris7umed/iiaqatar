<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Blog
Route::prefix('blog')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/category/{slug}', [PostController::class, 'byCategory'])->name('posts.category');
    Route::get('/tag/{slug}', [PostController::class, 'byTag'])->name('posts.tag');
    Route::get('/{slug}', [PostController::class, 'show'])->name('posts.show');
});

// Events
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('events.show');
});

// Courses
Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/{slug}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/{courseSlug}/lessons/{lessonSlug}', [CourseController::class, 'lesson'])->name('courses.lesson');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Event registration
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    
    // Course enrollment
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
});
