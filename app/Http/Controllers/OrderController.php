<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()->withCount('items')->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(string $code): View
    {
        $order = Order::where('order_code', $code)
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
