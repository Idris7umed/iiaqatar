<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::upcoming()
            ->orderBy('start_date', 'asc')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('events.show', compact('event'));
    }

    public function register(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'nullable|in:stripe,skipcash',
        ]);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $request->user_id,
            'status' => 'pending',
            'payment_status' => $event->price > 0 ? 'unpaid' : 'paid',
            'payment_method' => $request->payment_method,
            'amount_paid' => $event->price,
        ]);

        // If using SkipCash and event requires payment, return payment link URL
        if ($event->price > 0 && $request->payment_method === 'skipcash') {
            return response()->json([
                'message' => 'Registration successful',
                'registration' => $registration,
                'payment_required' => true,
                'payment_method' => 'skipcash',
                'payment_url' => route('skipcash.payment.event', ['event' => $event->id]),
            ], 201);
        }

        return response()->json([
            'message' => 'Registration successful',
            'registration' => $registration,
        ], 201);
    }
}
