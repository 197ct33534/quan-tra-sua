<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public const SUGAR_LEVELS = [100, 70, 50, 30, 0];

    public const ICE_LEVELS = [100, 70, 50, 0];

    public const SPICE_LEVELS = [
        'none' => 'Không cay',
        'mild' => 'Cay ít',
        'medium' => 'Cay vừa',
        'hot' => 'Cay nhiều',
    ];

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'unit_price',
        'quantity', 'size', 'sugar', 'ice', 'spice',
        'toppings', 'options_fee', 'line_total',
    ];

    protected $casts = [
        'toppings' => 'array',
        'unit_price' => 'integer',
        'options_fee' => 'integer',
        'line_total' => 'integer',
        'quantity' => 'integer',
        'sugar' => 'integer',
        'ice' => 'integer',
    ];

    public static function sugarLabel(?int $level): ?string
    {
        return match ($level) {
            100 => '100% đường (chuẩn)',
            70 => '70% đường',
            50 => '50% đường',
            30 => '30% đường',
            0 => 'Không đường',
            default => null,
        };
    }

    public static function iceLabel(?int $level): ?string
    {
        return match ($level) {
            100 => '100% đá (chuẩn)',
            70 => '70% đá',
            50 => '50% đá',
            0 => 'Không đá',
            default => null,
        };
    }

    public static function spiceLabel(?string $spice): ?string
    {
        return self::SPICE_LEVELS[$spice] ?? null;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
