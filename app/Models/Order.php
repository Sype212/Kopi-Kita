<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_number', 'user_id', 'order_type', 'table_number',
    'customer_name', 'subtotal', 'tax', 'total',
    'payment_method', 'bank_name', 'reference_number',
    'cash_received', 'cash_change', 'status',
])]
class Order extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'tax' => 'integer',
            'total' => 'integer',
            'cash_received' => 'integer',
            'cash_change' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Label untuk nomor pesanan yang tampil di UI.
     */
    public function orderLabel(): string
    {
        return '#'.$this->order_number;
    }

    /**
     * Format total dalam Rupiah.
     */
    public function formattedTotal(): string
    {
        return 'Rp'.number_format($this->total, 0, ',', '.');
    }

    /**
     * Lokasi pesanan: nama meja atau "Bawa pulang".
     */
    public function locationLabel(): string
    {
        if ($this->order_type === 'takeaway') {
            return 'Bawa pulang';
        }

        return $this->table_number ?? 'Meja -';
    }

    /**
     * Warna badge status.
     *
     * @return array{bg: string, text: string, label: string}
     */
    public function statusBadge(): array
    {
        return match ($this->status) {
            'diproses' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Diproses'],
            'siap_diambil' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Siap diambil'],
            'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Selesai'],
            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => ucfirst($this->status)],
        };
    }

    /**
     * Generate nomor pesanan berikutnya.
     */
    public static function generateOrderNumber(): string
    {
        $latest = self::orderByDesc('id')->first();
        if (! $latest) {
            return 'KK-001';
        }

        preg_match('/(\d+)$/', $latest->order_number, $matches);
        $nextNumber = isset($matches[1]) ? ((int) $matches[1]) + 1 : $latest->id + 1;

        while (self::where('order_number', 'KK-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT))->exists()) {
            $nextNumber++;
        }

        return 'KK-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
