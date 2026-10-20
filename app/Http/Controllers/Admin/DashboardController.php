<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statusCounts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'orders_total'   => Order::count(),
            'orders_pending' => $statusCounts['pending'] ?? 0,
            'revenue'        => Order::where('status', 'completed')->sum('total'),
            'products'       => Product::count(),
            'categories'     => Category::count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        // Top 5 sản phẩm bán chạy nhất (dựa vào order_items)
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->whereNotNull('product_name')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Doanh thu 7 ngày gần nhất
        $revenueByDay = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw("strftime('%Y-%m-%d', created_at) as date, SUM(total) as revenue")
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');

        // Đảm bảo đủ 7 ngày dù không có doanh thu
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $days[$date] = $revenueByDay[$date] ?? 0;
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'statusCounts', 'topProducts', 'days'));
    }
}
