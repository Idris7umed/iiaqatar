<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'category', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::with(['author', 'category', 'tags', 'comments.user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $post->increment('views_count');

        return view('posts.show', compact('post'));
    }

    public function byCategory($slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()
            ->with(['author', 'category', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('posts.index', compact('posts', 'category'));
    }

    public function byTag($slug)
    {
        $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()
            ->with(['author', 'category', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('posts.index', compact('posts', 'tag'));
    }
}
