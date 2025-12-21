<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::published()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $products->items(),
                'pagination' => [
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                ],
            ]);
        }

        return view('products.index', compact('products'));
    }

    public function show(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with('category')
            ->firstOrFail();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $product,
            ]);
        }

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
