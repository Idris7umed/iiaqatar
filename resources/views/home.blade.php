@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Welcome to IIAQATAR</h1>
            <p class="text-xl md:text-2xl mb-8">Islamic Institute of Advanced Quality Assurance Training & Research</p>
            <p class="text-lg mb-8 max-w-3xl mx-auto">Empowering professionals with world-class quality assurance training rooted in Islamic ethics and excellence</p>
            <div class="space-x-4">
                <a href="{{ route('courses.index') }}" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">Explore Courses</a>
                <a href="{{ route('about') }}" class="inline-block bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800">Learn More</a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Courses -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold mb-8">Featured Courses</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($featuredCourses as $course)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 bg-gray-200">
                @if($course->featured_image)
                <img src="{{ $course->featured_image }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div class="p-6">
                <span class="text-xs font-semibold text-blue-600 uppercase">{{ $course->level }}</span>
                <h3 class="text-xl font-bold mt-2 mb-2">{{ $course->title }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-blue-600">${{ $course->discount_price ?? $course->price }}</span>
                    <a href="{{ route('courses.show', $course->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Learn More →</a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-500">No courses available at the moment.</p>
        @endforelse
    </div>
</div>

<!-- Upcoming Events -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8">Upcoming Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($upcomingEvents as $event)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gray-200">
                    @if($event->featured_image)
                    <img src="{{ $event->featured_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-2">
                        {{ $event->start_date->format('M d, Y') }} • {{ $event->event_type }}
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                    <a href="{{ route('events.show', $event->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View Details →</a>
                </div>
            </div>
            @empty
            <p class="text-gray-500">No upcoming events at the moment.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Latest Blog Posts -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold mb-8">Latest Articles</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($featuredPosts as $post)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 bg-gray-200">
                @if($post->featured_image)
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div class="p-6">
                <div class="text-sm text-gray-500 mb-2">
                    {{ $post->published_at->format('M d, Y') }} • {{ $post->category->name ?? 'Uncategorized' }}
                </div>
                <h3 class="text-xl font-bold mb-2">{{ $post->title }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ $post->excerpt }}</p>
                <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Read More →</a>
            </div>
        </div>
        @empty
        <p class="text-gray-500">No posts available at the moment.</p>
        @endforelse
    </div>
</div>
@endsection
