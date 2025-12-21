<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::prefix('v1')->group(function () {
    // Blog posts
    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index']);
    Route::get('/posts/{slug}', [\App\Http\Controllers\PostController::class, 'show']);

    // Events
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index']);
    Route::get('/events/{slug}', [\App\Http\Controllers\EventController::class, 'show']);

    // Courses
    Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index']);
    Route::get('/courses/{slug}', [\App\Http\Controllers\CourseController::class, 'show']);

    // Products
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/products/{slug}', [\App\Http\Controllers\ProductController::class, 'show']);
});

// Protected API routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/events/{event}/register', [\App\Http\Controllers\EventController::class, 'register']);
    Route::post('/courses/{course}/enroll', [\App\Http\Controllers\CourseController::class, 'enroll']);
});
