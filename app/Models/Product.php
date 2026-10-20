<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected static function booted(): void
    {
        static::saved(function (Product $product) {
            if ($product->has_options && $product->sizes()->doesntExist()) {
                $product->seedDefaultSizes();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class)->orderBy('sort_order');
    }

    public function sizeFee(?string $size): int
    {
        if (! $size) {
            return 0;
        }

        return (int) ($this->sizes()->where('code', $size)->value('price') ?? 0);
    }

    public function seedDefaultSizes(): void
    {
        $defaults = [
            ['S', 'Nhỏ (S)', 0, 0],
            ['M', 'Vừa (M)', 5000, 1],
            ['L', 'Lớn (L)', 10000, 2],
        ];

        foreach ($defaults as [$code, $label, $price, $sort]) {
            $this->sizes()->create([
                'code' => $code,
                'label' => $label,
                'price' => $price,
                'sort_order' => $sort,
            ]);
        }
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        $demo = 'images/products/'.$this->slug.'.jpg';
        if (is_file(public_path($demo))) {
            return asset($demo);
        }

        return asset('images/products/default-drink.jpg');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
