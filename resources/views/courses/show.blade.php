@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            @if($course->featured_image)
            <div class="h-96 bg-gray-200 rounded-lg overflow-hidden mb-6">
                <img src="{{ $course->featured_image }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
            </div>
            @endif

            <div class="flex items-center gap-2 mb-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold uppercase">{{ $course->level }}</span>
                @if($course->is_featured)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">Featured</span>
                @endif
            </div>

            <h1 class="text-4xl font-bold mb-4">{{ $course->title }}</h1>

            <div class="flex items-center text-gray-600 mb-6">
                <span>By {{ $course->instructor->name }}</span>
                <span class="mx-2">•</span>
                <span>{{ $course->category->name ?? 'Uncategorized' }}</span>
            </div>

            <div class="prose prose-lg max-w-none mb-8">
                <h2 class="text-2xl font-bold mb-4">About This Course</h2>
                {!! nl2br(e($course->description)) !!}
            </div>

            @if($course->objectives)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">What You'll Learn</h2>
                <ul class="space-y-2">
                    @foreach($course->objectives as $objective)
                    <li class="flex items-start">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>{{ $objective }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($course->requirements)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Requirements</h2>
                <ul class="space-y-2">
                    @foreach($course->requirements as $requirement)
                    <li class="flex items-start">
                        <span class="text-gray-400 mr-2">•</span>
                        <span>{{ $requirement }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Curriculum -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Curriculum</h2>
                <div class="space-y-2">
                    @foreach($course->lessons as $lesson)
                    <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold">{{ $lesson->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $lesson->duration }} minutes</p>
                        </div>
                        @if($lesson->is_free)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-sm">Free Preview</span>
                        @else
                        <span class="text-gray-400">🔒</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <div class="mb-6">
                    @if($course->discount_price)
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-blue-600">${{ $course->discount_price }}</span>
                        <span class="text-xl line-through text-gray-400">${{ $course->price }}</span>
                    </div>
                    @else
                    <span class="text-3xl font-bold text-blue-600">${{ $course->price }}</span>
                    @endif
                </div>

                <button class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 mb-4">
                    Enroll Now
                </button>

                <div class="space-y-4 border-t pt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">⏱️ Duration</span>
                        <span class="font-semibold">{{ $course->duration }} minutes</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">📚 Lessons</span>
                        <span class="font-semibold">{{ $course->lessons->count() }} lessons</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">📊 Level</span>
                        <span class="font-semibold capitalize">{{ $course->level }}</span>
                    </div>
                    @if($course->max_students)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">👥 Max Students</span>
                        <span class="font-semibold">{{ $course->max_students }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
