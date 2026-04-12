<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_phone', 'service_id',
        'material_type', 'kilos', 'minutes_per_kilo', 'subtotal',
        'addons_total', 'total_amount', 'amount_paid', 'balance',
        'payment_status', 'order_status', 'created_by',
    ];

    protected $casts = [
        'kilos' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function addons()
    {
        return $this->hasMany(TransactionAddon::class);
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return 'JML-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
