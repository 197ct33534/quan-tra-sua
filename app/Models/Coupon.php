<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public const TYPE_PERCENT = 'percent';

    public const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'code', 'type', 'value', 'min_order_value', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'value' => 'integer',
        'min_order_value' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isCurrentlyValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return ! $this->expires_at || $this->expires_at->isFuture();
    }

    public function appliesTo(int $subtotal): bool
    {
        return $this->isCurrentlyValid() && $subtotal >= $this->min_order_value;
    }

    public function discountFor(int $subtotal): int
    {
        if (! $this->appliesTo($subtotal)) {
            return 0;
        }

        $amount = $this->type === self::TYPE_PERCENT
            ? intdiv($subtotal * $this->value, 100)
            : $this->value;

        return min(max(0, $amount), $subtotal);
    }
}
