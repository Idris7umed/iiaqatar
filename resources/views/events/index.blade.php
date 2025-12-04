@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Upcoming Events</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 bg-gray-200">
                @if($event->featured_image)
                <img src="{{ $event->featured_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div class="p-6">
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <span>{{ $event->start_date->format('M d, Y') }}</span>
                    <span class="mx-2">•</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $event->event_type }}</span>
                </div>
                <h2 class="text-xl font-bold mb-2">{{ $event->title }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-blue-600">${{ $event->price }}</span>
                    <a href="{{ route('events.show', $event->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View Details →</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <p class="text-gray-500 text-center">No upcoming events at the moment.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $events->links() }}
    </div>
</div>
@endsection
