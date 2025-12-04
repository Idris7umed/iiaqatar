@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Blog</h1>
    
    @if(isset($category))
    <div class="mb-6">
        <span class="text-gray-600">Category: </span>
        <span class="text-blue-600 font-semibold">{{ $category->name }}</span>
    </div>
    @endif

    @if(isset($tag))
    <div class="mb-6">
        <span class="text-gray-600">Tag: </span>
        <span class="text-blue-600 font-semibold">{{ $tag->name }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
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
                <h2 class="text-xl font-bold mb-2">{{ $post->title }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ $post->excerpt }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">By {{ $post->author->name }}</span>
                    <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Read More →</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <p class="text-gray-500 text-center">No posts found.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection
