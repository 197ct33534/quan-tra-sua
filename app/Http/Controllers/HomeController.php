<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $featured = Product::available()
            ->latest()
            ->take(8)
            ->get();

        $byCategory = Category::where('is_active', true)
            ->with(['availableProducts' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('categories', 'featured', 'byCategory'));
    }
}
