<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::published()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with('category')
            ->firstOrFail();

        return view('products.show', compact('product'));
    }

    public function virtual()
    {
        $products = Product::published()
            ->virtual()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function physical()
    {
        $products = Product::published()
            ->physical()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('products.index', compact('products'));
    }
}
