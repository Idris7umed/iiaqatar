@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            @if($product->featured_image)
            <img src="{{ $product->featured_image }}" alt="{{ $product->title }}" class="w-full rounded-lg shadow-lg">
            @else
            <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                <span class="text-gray-400 text-xl">No image available</span>
            </div>
            @endif
        </div>

        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="px-3 py-1 rounded text-sm font-semibold {{ $product->product_type === 'virtual' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($product->product_type) }}
                </span>
                @if($product->is_featured)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-sm font-semibold">Featured</span>
                @endif
                @if($product->category)
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded text-sm">{{ $product->category->name }}</span>
                @endif
            </div>

            <h1 class="text-4xl font-bold mb-4">{{ $product->title }}</h1>

            <div class="mb-6">
                @if($product->discount_price)
                <span class="text-3xl line-through text-gray-400 mr-4">${{ $product->price }}</span>
                <span class="text-5xl font-bold text-blue-600">${{ $product->discount_price }}</span>
                @else
                <span class="text-5xl font-bold text-blue-600">${{ $product->price }}</span>
                @endif
            </div>

            @if($product->sku)
            <p class="text-gray-600 mb-4">SKU: {{ $product->sku }}</p>
            @endif

            @if($product->product_type === 'physical' && $product->stock_quantity !== null)
            <div class="mb-6">
                @if($product->stock_quantity > 0)
                <span class="text-green-600 font-semibold">In Stock ({{ $product->stock_quantity }} available)</span>
                @else
                <span class="text-red-600 font-semibold">Out of Stock</span>
                @endif
            </div>
            @endif

            <div class="prose max-w-none mb-6">
                {!! nl2br(e($product->description)) !!}
            </div>

            @if($product->features && count($product->features) > 0)
            <div class="mb-6">
                <h3 class="text-xl font-bold mb-3">Features</h3>
                <ul class="list-disc list-inside space-y-2">
                    @foreach($product->features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <button class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition">
                Add to Cart
            </button>
        </div>
    </div>
</div>
@endsection
