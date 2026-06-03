<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statusCounts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'orders_total' => Order::count(),
            'orders_pending' => $statusCounts['pending'] ?? 0,
            'revenue' => Order::where('status', 'completed')->sum('total'),
            'products' => Product::count(),
            'categories' => Category::count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'statusCounts'));
    }
}
