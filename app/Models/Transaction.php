<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_phone', 'customer_id', 'service_id',
        'material_type', 'kilos', 'number_of_loads', 'minutes_per_kilo', 'subtotal',
        'addons_total', 'total_amount', 'amount_paid', 'balance',
        'payment_status', 'order_status', 'created_by',
        'machine_id', 'cycle_type', 'duration_minutes', 'machine_started_at',
    ];

    protected $casts = [
        'kilos' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'machine_started_at' => 'datetime',
        'duration_minutes' => 'integer',
        'number_of_loads' => 'integer',
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

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function machineLogs()
    {
        return $this->hasMany(MachineLog::class);
    }

    public function getMachineStatusAttribute()
    {
        if (! $this->machine_started_at || ! $this->duration_minutes) {
            return null;
        }

        $end = $this->machine_started_at->copy()->addMinutes($this->duration_minutes);

        if ($end->isPast()) {
            return 'completed';
        }

        return 'running';
    }

    public function getRemainingSecondsAttribute()
    {
        if (! $this->machine_started_at || ! $this->duration_minutes || $this->machine_status !== 'running') {
            return 0;
        }

        $end = $this->machine_started_at->copy()->addMinutes($this->duration_minutes);

        return max(0, Carbon::now()->diffInSeconds($end, false));
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

        return 'JML-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
