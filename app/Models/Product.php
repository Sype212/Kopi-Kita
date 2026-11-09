<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'price', 'stock', 'unit', 'image', 'is_active'])]
class Product extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Format harga dalam Rupiah.
     */
    public function formattedPrice(): string
    {
        return 'Rp'.number_format($this->price, 0, ',', '.');
    }

    /**
     * Cek apakah stok produk menipis (di bawah 10).
     */
    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= 10;
    }

    /**
     * Cek apakah produk habis.
     */
    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }
}
