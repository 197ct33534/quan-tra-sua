<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where('order_code', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items', 'user');

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::STATUSES))],
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
