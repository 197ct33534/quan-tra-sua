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

        // Lọc giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price * 1000);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price * 1000);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'default');
        $query = match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest'     => $query->orderByDesc('created_at'),
            default      => $query->orderBy('sort_order'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('menu', compact('categories', 'products', 'activeCategory', 'sort'));
    }
}
