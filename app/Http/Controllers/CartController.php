<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'size' => ['nullable', 'in:S,M,L'],
            'toppings' => ['nullable', 'array'],
            'toppings.*' => ['integer', 'exists:toppings,id'],
        ]);

        $product = Product::available()->findOrFail($data['product_id']);

        $this->cart->add(
            $product,
            (int) $data['quantity'],
            $data['size'] ?? null,
            $data['toppings'] ?? []
        );

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
