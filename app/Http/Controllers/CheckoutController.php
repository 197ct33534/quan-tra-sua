<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PricingService;
use App\Services\TelegramNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected PricingService $pricing,
    ) {
    }

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        if ($message = $this->cart->unavailableMessage()) {
            return redirect()->route('cart.index')->with('error', $message);
        }

        $fulfillment = old('fulfillment', 'delivery');
        $quote = $this->pricing->quote($this->cart->subtotal(), $fulfillment);

        return view('checkout.index', [
            'items' => $this->cart->items(),
            'quote' => $quote,
            'fulfillment' => $fulfillment,
            'user' => Auth::user(),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        if ($message = $this->cart->unavailableMessage()) {
            return redirect()->route('cart.index')->with('error', $message);
        }

        $data = $request->validated();
        $subtotal = $this->cart->subtotal();
        $quote = $this->pricing->quote($subtotal, $data['fulfillment']);

        if (session(PricingService::SESSION_KEY) && $quote['coupon'] === null) {
            return back()->withInput()->with('error', $quote['notice'] ?? 'Mã giảm giá không còn hiệu lực.');
        }

        $items = $this->cart->items();
        $address = $data['fulfillment'] === 'delivery' ? trim($data['address']) : null;

        $order = DB::transaction(function () use ($data, $quote, $items, $address) {
            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'user_id' => Auth::id(),
                'customer_name' => trim($data['customer_name']),
                'phone' => trim($data['phone']),
                'address' => $address,
                'fulfillment' => $data['fulfillment'],
                'note' => $data['note'] ?? null,
                'subtotal' => $quote['subtotal'],
                'discount' => $quote['discount'],
                'coupon_code' => $quote['coupon']?->code,
                'shipping_fee' => $quote['shipping_fee'],
                'total' => $quote['total'],
                'payment_method' => $data['payment_method'],
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
                    'sugar' => $item['sugar'] ?? null,
                    'ice' => $item['ice'] ?? null,
                    'spice' => $item['spice'] ?? null,
                    'note' => $item['note'] ?? null,
                    'toppings' => $item['toppings'],
                    'options_fee' => $item['options_fee'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->cart->clear();
        $this->pricing->forgetCoupon();
        app(TelegramNotifier::class)->notifyNewOrder($order);
        session(['recent_order' => [
            'code' => $order->order_code,
            'phone' => $order->phone,
        ]]);

        $redirect = Auth::check()
            ? route('orders.show', $order->order_code)
            : route('checkout.success');

        return redirect($redirect)->with('success', 'Đặt hàng thành công! Mã đơn: ' . $order->order_code);
    }

    public function success(): View
    {
        $recent = session('recent_order');
        abort_unless(is_array($recent), 404);

        $order = Order::where('order_code', $recent['code'] ?? '')
            ->where('phone', $recent['phone'] ?? '')
            ->with('items')
            ->firstOrFail();

        return view('orders.show', [
            'order' => $order,
            'placed' => true,
        ]);
    }

    protected function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
