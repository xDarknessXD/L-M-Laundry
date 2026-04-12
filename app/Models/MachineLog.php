<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MachineLog extends Model
{
    protected $fillable = [
        'machine_id', 'cycle_type', 'load_kilos', 'duration_minutes',
        'start_time', 'end_time', 'staff_id', 'status',
    ];

    protected $casts = [
        'load_kilos' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function getRemainingTimeAttribute()
    {
        if (! $this->end_time || $this->status === 'completed') {
            return null;
        }
        $now = Carbon::now();
        $end = Carbon::parse($this->end_time);
        if ($end->isPast()) {
            return 0;
        }

        return $now->diffInSeconds($end);
    }
}
