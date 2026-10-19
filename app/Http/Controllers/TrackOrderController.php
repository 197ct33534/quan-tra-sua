<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackOrderController extends Controller
{
    public function create(): View
    {
        return view('orders.track');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->credentials($request);
        $order = $this->findOrder($data['order_code'], $data['phone']);

        if (! $order) {
            return back()->withInput()->with('error', 'Không tìm thấy đơn hàng khớp mã và số điện thoại.');
        }

        session(['tracked_order' => [
            'code' => $order->order_code,
            'phone' => $order->phone,
        ]]);

        return redirect()->route('track.result');
    }

    public function result(): View|RedirectResponse
    {
        $order = $this->orderFromSession();
        if (! $order) {
            return redirect()->route('track.form')->with('error', 'Vui lòng nhập mã đơn và số điện thoại.');
        }

        return view('orders.show', [
            'order' => $order,
            'tracking' => true,
        ]);
    }

    public function cancel(Request $request): RedirectResponse
    {
        $data = $this->credentials($request);
        $order = $this->findOrder($data['order_code'], $data['phone']);

        if (! $order) {
            return redirect()->route('track.form')->with('error', 'Không tìm thấy đơn hàng khớp mã và số điện thoại.');
        }

        if (! $order->canBeCancelled()) {
            session(['tracked_order' => ['code' => $order->order_code, 'phone' => $order->phone]]);

            return redirect()->route('track.result')->with('error', 'Chỉ hủy được đơn khi đang chờ xác nhận hoặc đã xác nhận.');
        }

        $order->update(['status' => 'cancelled']);
        session(['tracked_order' => ['code' => $order->order_code, 'phone' => $order->phone]]);

        return redirect()->route('track.result')->with('success', 'Đã hủy đơn hàng.');
    }

    /**
     * @return array{order_code: string, phone: string}
     */
    protected function credentials(Request $request): array
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'max:32'],
            'phone' => ['required', 'string', 'max:20'],
        ], [], [
            'order_code' => 'mã đơn hàng',
            'phone' => 'số điện thoại',
        ]);

        return [
            'order_code' => strtoupper(trim($data['order_code'])),
            'phone' => trim($data['phone']),
        ];
    }

    protected function findOrder(string $code, string $phone): ?Order
    {
        return Order::where('order_code', $code)
            ->where('phone', $phone)
            ->with('items')
            ->first();
    }

    protected function orderFromSession(): ?Order
    {
        $tracked = session('tracked_order');
        if (! is_array($tracked)) {
            return null;
        }

        return $this->findOrder((string) ($tracked['code'] ?? ''), (string) ($tracked['phone'] ?? ''));
    }
}
