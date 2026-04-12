<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAddon extends Model
{
    protected $fillable = [
        'transaction_id', 'inventory_item_id', 'quantity',
        'price', 'is_customer_own', 'custom_item_name',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_customer_own' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
