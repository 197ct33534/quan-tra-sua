<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    protected $fillable = [
        'name', 'price', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
