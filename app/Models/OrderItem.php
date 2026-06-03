<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'unit_price',
        'quantity', 'size', 'toppings', 'options_fee', 'line_total',
    ];

    protected $casts = [
        'toppings' => 'array',
        'unit_price' => 'integer',
        'options_fee' => 'integer',
        'line_total' => 'integer',
        'quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
