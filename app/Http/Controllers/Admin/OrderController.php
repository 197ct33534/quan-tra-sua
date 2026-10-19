<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
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
            ->appends($request->except('partial'));

        if ($request->boolean('partial')) {
            return view('admin.orders._board', compact('orders'));
        }

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
            'latestId' => (int) Order::max('id'),
        ]);
    }

    public function checkNew(Request $request): JsonResponse
    {
        $afterId = (int) $request->query('after_id', 0);

        return response()->json([
            'pending_count' => Order::where('status', 'pending')->count(),
            'latest_id' => (int) Order::max('id'),
            'new_count' => Order::where('id', '>', $afterId)->count(),
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

        if (! $order->canTransitionTo($data['status'])) {
            return back()->with('error', 'Không thể chuyển trạng thái này. Chỉ được đi từng bước, hoặc hủy khi đơn đang chờ xác nhận / đã xác nhận.');
        }

        $order->update($data);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function print(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.print', compact('order'));
    }
}
