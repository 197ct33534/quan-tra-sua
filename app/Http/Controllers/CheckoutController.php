<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /** Phí giao hàng cố định */
    protected int $shippingFee = 15000;

    public function __construct(protected CartService $cart)
    {
    }

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        return view('checkout.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'shippingFee' => $this->shippingFee,
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [], [
            'customer_name' => 'họ tên',
            'phone' => 'số điện thoại',
            'address' => 'địa chỉ',
        ]);

        $subtotal = $this->cart->subtotal();
        $items = $this->cart->items();

        $order = DB::transaction(function () use ($data, $subtotal, $items) {
            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'user_id' => Auth::id(),
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'note' => $data['note'] ?? null,
                'subtotal' => $subtotal,
                'shipping_fee' => $this->shippingFee,
                'total' => $subtotal + $this->shippingFee,
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'toppings' => $item['toppings'],
                    'options_fee' => $item['options_fee'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('orders.show', $order->order_code)
            ->with('success', 'Đặt hàng thành công! Mã đơn: ' . $order->order_code);
    }

    protected function generateOrderCode(): string
    {
        do {
            $code = 'TS' . now()->format('ymd') . strtoupper(Str::random(4));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
