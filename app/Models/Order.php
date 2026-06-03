<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'customer_name', 'phone', 'address', 'note',
        'subtotal', 'shipping_fee', 'total', 'payment_method', 'status',
    ];

    protected $casts = [
        'subtotal' => 'integer',
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
