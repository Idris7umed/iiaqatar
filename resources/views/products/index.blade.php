@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Our Products</h1>
        <div class="flex gap-2">
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">All</a>
            <a href="{{ route('products.virtual') }}" class="px-4 py-2 bg-purple-100 text-purple-800 rounded hover:bg-purple-200">Virtual</a>
            <a href="{{ route('products.physical') }}" class="px-4 py-2 bg-green-100 text-green-800 rounded hover:bg-green-200">Physical</a>
        </div>
    </div>

    <div id="vue-app">
        <products-list :initial-products="{{ $products->toJson() }}"></products-list>
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
