@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article>
        <header class="mb-8">
            @if($post->featured_image)
            <div class="h-96 bg-gray-200 rounded-lg overflow-hidden mb-6">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
            @endif
            
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <span>{{ $post->published_at->format('F d, Y') }}</span>
                <span class="mx-2">•</span>
                <a href="{{ route('posts.category', $post->category->slug) }}" class="text-blue-600 hover:text-blue-800">
                    {{ $post->category->name }}
                </a>
                <span class="mx-2">•</span>
                <span>{{ $post->views_count }} views</span>
            </div>
            
            <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>
            
            <div class="flex items-center mb-6">
                <div class="text-gray-700">
                    By <span class="font-semibold">{{ $post->author->name }}</span>
                </div>
            </div>

            @if($post->tags->count())
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($post->tags as $tag)
                <a href="{{ route('posts.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>
            @endif
        </header>

        <div class="prose prose-lg max-w-none mb-12">
            {!! nl2br(e($post->content)) !!}
        </div>
    </article>

    <!-- Comments Section -->
    <div class="border-t pt-8">
        <h2 class="text-2xl font-bold mb-6">Comments</h2>
        @forelse($post->comments()->approved()->whereNull('parent_id')->get() as $comment)
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center mb-2">
                <span class="font-semibold">{{ $comment->user->name }}</span>
                <span class="mx-2 text-gray-400">•</span>
                <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-gray-700">{{ $comment->content }}</p>
        </div>
        @empty
        <p class="text-gray-500">No comments yet.</p>
        @endforelse
    </div>
</div>
@endsection
