<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'type', 'price_per_kilo', 'wash_price', 'wash_minutes',
        'dry_price', 'dry_minutes', 'fold_price', 'minimum_kilos', 'is_active',
    ];

    protected $casts = [
        'price_per_kilo' => 'decimal:2',
        'wash_price' => 'decimal:2',
        'dry_price' => 'decimal:2',
        'fold_price' => 'decimal:2',
        'minimum_kilos' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
