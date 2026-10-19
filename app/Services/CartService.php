<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Topping;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class CartService
{
    protected string $key = 'cart';

    /**
     * Mỗi item: rowId => product, size, sugar, ice, spice, toppings, giá snapshot.
     */
    public function items(): array
    {
        return Session::get($this->key, []);
    }

    /**
     * @param  array{sugar?: int|string|null, ice?: int|string|null, spice?: string|null}  $variants
     */
    public function add(Product $product, int $quantity = 1, ?string $size = null, array $toppingIds = [], array $variants = []): void
    {
        $sugar = null;
        $ice = null;
        $spice = null;
        $sizeFee = 0;
        $sizeLabel = null;
        $sizeRow = null;

        if ($product->has_options) {
            $sizeRow = $this->resolveSize($product, $size);
            $size = $sizeRow->code;
            $sizeFee = (int) $sizeRow->price;
            $sizeLabel = $this->formatSizeLabel($sizeRow);
            $sugar = $this->normalizeLevel($variants['sugar'] ?? 100, OrderItem::SUGAR_LEVELS, 'Mức đường không hợp lệ.');
            $ice = $this->normalizeLevel($variants['ice'] ?? 100, OrderItem::ICE_LEVELS, 'Mức đá không hợp lệ.');
        } else {
            $size = null;
            $spice = $this->normalizeSpice($variants['spice'] ?? 'none');
        }

        $toppings = [];
        $toppingsFee = 0;
        if ($product->has_options && ! empty($toppingIds)) {
            $rows = Topping::active()->whereIn('id', $toppingIds)->get();
            foreach ($rows as $t) {
                $toppings[] = ['id' => $t->id, 'name' => $t->name, 'price' => (int) $t->price];
                $toppingsFee += (int) $t->price;
            }
        }

        $optionsFee = $sizeFee + $toppingsFee;
        $unitPrice = (int) $product->price + $optionsFee;
        $rowId = $this->makeRowId($product->id, $size, $sugar, $ice, $spice, array_column($toppings, 'id'));

        $cart = $this->items();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $quantity;
        } else {
            $cart[$rowId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image_url,
                'base_price' => (int) $product->price,
                'size' => $size,
                'size_label' => $sizeLabel,
                'sugar' => $sugar,
                'sugar_label' => OrderItem::sugarLabel($sugar),
                'ice' => $ice,
                'ice_label' => OrderItem::iceLabel($ice),
                'spice' => $spice,
                'spice_label' => OrderItem::spiceLabel($spice),
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

    public function unavailableMessage(): ?string
    {
        foreach ($this->items() as $item) {
            $product = Product::find($item['product_id'] ?? 0);
            if (! $product || ! $product->is_available) {
                return 'Món ' . ($item['name'] ?? 'này') . ' hiện vừa hết hàng, vui lòng xóa khỏi giỏ để tiếp tục.';
            }

            foreach ($item['toppings'] ?? [] as $topping) {
                $row = Topping::where('id', $topping['id'] ?? 0)->where('is_active', true)->first();
                if (! $row) {
                    return 'Topping ' . ($topping['name'] ?? 'đã chọn') . ' hiện vừa hết hàng, vui lòng xóa khỏi giỏ để tiếp tục.';
                }
            }
        }

        return null;
    }

    protected function resolveSize(Product $product, ?string $size): ProductSize
    {
        $query = $product->sizes();
        $row = $size
            ? $query->where('code', $size)->first()
            : $product->sizes()->orderBy('sort_order')->first();

        if (! $row) {
            throw new InvalidArgumentException('Sản phẩm chưa có kích cỡ hợp lệ.');
        }

        return $row;
    }

    protected function formatSizeLabel(ProductSize $size): string
    {
        if ((int) $size->price <= 0) {
            return $size->label;
        }

        return $size->label . ' +' . number_format((int) $size->price, 0, ',', '.') . 'đ';
    }

    /**
     * @param  array<int, int>  $allowed
     */
    protected function normalizeLevel(int|string|null $value, array $allowed, string $message): int
    {
        $level = (int) $value;
        if (! in_array($level, $allowed, true)) {
            throw new InvalidArgumentException($message);
        }

        return $level;
    }

    protected function normalizeSpice(?string $spice): string
    {
        $spice = $spice ?: 'none';
        if (! array_key_exists($spice, OrderItem::SPICE_LEVELS)) {
            throw new InvalidArgumentException('Mức cay không hợp lệ.');
        }

        return $spice;
    }

    protected function makeRowId(int $productId, ?string $size, ?int $sugar, ?int $ice, ?string $spice, array $toppingIds): string
    {
        sort($toppingIds);

        return md5(implode('|', [
            $productId,
            $size ?? '',
            $sugar ?? '',
            $ice ?? '',
            $spice ?? '',
            implode(',', $toppingIds),
        ]));
    }
}
