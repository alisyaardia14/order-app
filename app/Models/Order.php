<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'table_number',
        'customer_name',
        'customer_phone',
        'notes',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'processing' => 'Diproses',
            'ready' => 'Belum Dibayar',
            'completed' => 'Sudah Bayar',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
