<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::published()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $upcomingEvents = Event::upcoming()
            ->take(3)
            ->get();

        $featuredCourses = Course::published()
            ->where('is_featured', true)
            ->take(4)
            ->get();

        return view('home', compact('featuredPosts', 'upcomingEvents', 'featuredCourses'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
