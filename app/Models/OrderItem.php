<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'product_id', 'quantity', 'unit_price', 'subtotal'])]
class OrderItem extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Format harga satuan dalam Rupiah.
     */
    public function formattedUnitPrice(): string
    {
        return 'Rp'.number_format($this->unit_price, 0, ',', '.');
    }

    /**
     * Format subtotal dalam Rupiah.
     */
    public function formattedSubtotal(): string
    {
        return 'Rp'.number_format($this->subtotal, 0, ',', '.');
    }
}
