<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Topping;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->available()
            ->with('category')
            ->firstOrFail();

        $toppings = $product->has_options
            ? Topping::active()->orderBy('name')->get()
            : collect();

        $related = Product::available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'toppings', 'related'));
    }
}
