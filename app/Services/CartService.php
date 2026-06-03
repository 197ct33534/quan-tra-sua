<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Topping;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $key = 'cart';

    /**
     * Toàn bộ item trong giỏ.
     * Mỗi item: rowId => [product_id, name, price, quantity, size, size_label,
     *                     toppings (array of [id,name,price]), options_fee, unit_price, line_total]
     */
    public function items(): array
    {
        return Session::get($this->key, []);
    }

    public function add(Product $product, int $quantity = 1, ?string $size = null, array $toppingIds = []): void
    {
        $sizeFee = $this->sizeFee($size);

        $toppings = [];
        $toppingsFee = 0;
        if ($product->has_options && ! empty($toppingIds)) {
            $rows = Topping::active()->whereIn('id', $toppingIds)->get();
            foreach ($rows as $t) {
                $toppings[] = ['id' => $t->id, 'name' => $t->name, 'price' => $t->price];
                $toppingsFee += $t->price;
            }
        }

        if (! $product->has_options) {
            $size = null;
            $sizeFee = 0;
        }

        $optionsFee = $sizeFee + $toppingsFee;
        $unitPrice = $product->price + $optionsFee;

        $rowId = $this->makeRowId($product->id, $size, array_column($toppings, 'id'));

        $cart = $this->items();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $quantity;
        } else {
            $cart[$rowId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image_url,
                'base_price' => $product->price,
                'size' => $size,
                'size_label' => $size ? $this->sizeLabel($size) : null,
                'toppings' => $toppings,
                'options_fee' => $optionsFee,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
            ];
        }

        $cart[$rowId]['line_total'] = $cart[$rowId]['unit_price'] * $cart[$rowId]['quantity'];

        Session::put($this->key, $cart);
    }

    public function update(string $rowId, int $quantity): void
    {
        $cart = $this->items();
        if (! isset($cart[$rowId])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$rowId]);
        } else {
            $cart[$rowId]['quantity'] = $quantity;
            $cart[$rowId]['line_total'] = $cart[$rowId]['unit_price'] * $quantity;
        }

        Session::put($this->key, $cart);
    }

    public function remove(string $rowId): void
    {
        $cart = $this->items();
        unset($cart[$rowId]);
        Session::put($this->key, $cart);
    }

    public function clear(): void
    {
        Session::forget($this->key);
    }

    public function subtotal(): int
    {
        return (int) collect($this->items())->sum('line_total');
    }

    public function count(): int
    {
        return (int) collect($this->items())->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return empty($this->items());
    }

    protected function sizeFee(?string $size): int
    {
        return match ($size) {
            'M' => 5000,
            'L' => 10000,
            default => 0,
        };
    }

    public function sizeLabel(string $size): string
    {
        return match ($size) {
            'S' => 'Nhỏ (S)',
            'M' => 'Vừa (M) +5.000đ',
            'L' => 'Lớn (L) +10.000đ',
            default => $size,
        };
    }

    protected function makeRowId(int $productId, ?string $size, array $toppingIds): string
    {
        sort($toppingIds);

        return md5($productId . '|' . ($size ?? '') . '|' . implode(',', $toppingIds));
    }
}
