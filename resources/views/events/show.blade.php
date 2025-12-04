@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($event->featured_image)
    <div class="h-96 bg-gray-200 rounded-lg overflow-hidden mb-8">
        <img src="{{ $event->featured_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <div class="flex items-center gap-2 mb-4">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $event->event_type }}</span>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $event->status }}</span>
        </div>

        <h1 class="text-4xl font-bold mb-4">{{ $event->title }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 text-gray-700">
            <div>
                <h3 class="font-semibold mb-2">📅 Date & Time</h3>
                <p>{{ $event->start_date->format('F d, Y - h:i A') }}</p>
                <p>to {{ $event->end_date->format('F d, Y - h:i A') }}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">📍 Location</h3>
                <p>{{ $event->location }}</p>
            </div>
            @if($event->max_attendees)
            <div>
                <h3 class="font-semibold mb-2">👥 Max Attendees</h3>
                <p>{{ $event->max_attendees }} people</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold mb-2">💰 Price</h3>
                <p class="text-2xl font-bold text-blue-600">${{ $event->price }}</p>
            </div>
        </div>

        @if($event->registration_deadline)
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400">
            <p class="text-yellow-800">
                <strong>Registration Deadline:</strong> {{ $event->registration_deadline->format('F d, Y') }}
            </p>
        </div>
        @endif

        <div class="prose prose-lg max-w-none mb-8">
            <h2 class="text-2xl font-bold mb-4">About This Event</h2>
            {!! nl2br(e($event->description)) !!}
        </div>

        <div class="flex gap-4">
            <button class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Register Now
            </button>
            <button class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300">
                Share Event
            </button>
        </div>
    </div>
</div>
@endsection
