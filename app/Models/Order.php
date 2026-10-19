<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const CANCELABLE = ['pending', 'confirmed'];

    protected $fillable = [
        'order_code', 'user_id', 'customer_name', 'phone', 'address', 'note',
        'fulfillment', 'subtotal', 'discount', 'coupon_code', 'shipping_fee',
        'total', 'payment_method', 'status',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'shipping_fee' => 'integer',
        'total' => 'integer',
    ];

    public const STATUSES = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'preparing' => 'Đang pha chế',
        'delivering' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, self::CANCELABLE, true);
    }

    public function canTransitionTo(string $status): bool
    {
        if ($status === $this->status) {
            return true;
        }

        if ($status === 'cancelled') {
            return $this->canBeCancelled();
        }

        $flow = ['pending', 'confirmed', 'preparing', 'delivering', 'completed'];
        $from = array_search($this->status, $flow, true);
        $to = array_search($status, $flow, true);

        return $from !== false && $to !== false && $to === $from + 1;
    }

    /**
     * @return array<string, string>
     */
    public function nextStatuses(): array
    {
        return array_filter(
            self::STATUSES,
            fn (string $label, string $status) => $this->canTransitionTo($status),
            ARRAY_FILTER_USE_BOTH
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'secondary',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'delivering' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
