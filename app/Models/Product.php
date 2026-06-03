<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price',
        'image', 'is_available', 'has_options', 'sort_order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'has_options' => 'boolean',
        'price' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // Ảnh placeholder khi sản phẩm chưa có hình
        return 'https://placehold.co/400x300?text=' . urlencode($this->name);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
