<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected PricingService $pricing,
    ) {
    }

    public function index(): View
    {
        $quote = $this->pricing->quote($this->cart->subtotal());

        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $quote['subtotal'],
            'discount' => $quote['discount'],
            'couponCode' => $quote['coupon']?->code,
            'couponNotice' => $quote['notice'],
        ]);
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ], [], ['code' => 'mã giảm giá']);

        $code = strtoupper(trim($data['code']));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon || ! $coupon->isCurrentlyValid()) {
            return back()->with('error', 'Mã giảm giá không tồn tại hoặc đã hết hạn.');
        }

        if ($this->cart->subtotal() < $coupon->min_order_value) {
            return back()->with('error', 'Đơn chưa đạt giá trị tối thiểu của mã giảm giá.');
        }

        $this->pricing->rememberCoupon($coupon->code);

        return back()->with('success', 'Đã áp dụng mã ' . $coupon->code . '.');
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->pricing->forgetCoupon();

        return back()->with('success', 'Đã gỡ mã giảm giá.');
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'size' => ['nullable', 'string', 'max:8'],
            'toppings' => ['nullable', 'array'],
            'toppings.*' => ['integer', 'exists:toppings,id'],
            'sugar' => ['nullable', Rule::in(OrderItem::SUGAR_LEVELS)],
            'ice' => ['nullable', Rule::in(OrderItem::ICE_LEVELS)],
            'spice' => ['nullable', Rule::in(array_keys(OrderItem::SPICE_LEVELS))],
        ]);

        $product = Product::available()->findOrFail($data['product_id']);

        try {
            $this->cart->add(
                $product,
                (int) $data['quantity'],
                $data['size'] ?? null,
                $data['toppings'] ?? [],
                [
                    'sugar' => $data['sugar'] ?? null,
                    'ice' => $data['ice'] ?? null,
                    'spice' => $data['spice'] ?? null,
                ]
            );
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'row_id' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0', 'max:50'],
        ]);

        $this->cart->update($data['row_id'], (int) $data['quantity']);

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'row_id' => ['required', 'string'],
        ]);

        $this->cart->remove($data['row_id']);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ.');
    }
}
