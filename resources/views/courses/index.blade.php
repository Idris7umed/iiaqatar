@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Our Courses</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 bg-gray-200">
                @if($course->featured_image)
                <img src="{{ $course->featured_image }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold uppercase">{{ $course->level }}</span>
                    @if($course->is_featured)
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">Featured</span>
                    @endif
                </div>
                <h2 class="text-xl font-bold mb-2">{{ $course->title }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <span>👤 {{ $course->instructor->name }}</span>
                    <span class="mx-2">•</span>
                    <span>⏱️ {{ $course->duration }} min</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        @if($course->discount_price)
                        <span class="text-lg line-through text-gray-400">${{ $course->price }}</span>
                        <span class="text-2xl font-bold text-blue-600 ml-2">${{ $course->discount_price }}</span>
                        @else
                        <span class="text-2xl font-bold text-blue-600">${{ $course->price }}</span>
                        @endif
                    </div>
                    <a href="{{ route('courses.show', $course->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Learn More →</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <p class="text-gray-500 text-center">No courses available at the moment.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $courses->links() }}
    </div>
</div>
@endsection
