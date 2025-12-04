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
        ]);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $request->user_id,
            'status' => 'pending',
            'payment_status' => $event->price > 0 ? 'unpaid' : 'paid',
            'amount_paid' => $event->price,
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'registration' => $registration,
        ], 201);
    }
}
