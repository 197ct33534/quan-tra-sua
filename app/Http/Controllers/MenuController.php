<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $query = Product::available()->with('category');

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = $categories->firstWhere('slug', $request->category);
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $products = $query->orderBy('sort_order')->paginate(12)->withQueryString();

        return view('menu', compact('categories', 'products', 'activeCategory'));
    }
}
