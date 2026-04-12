<?php

namespace App\Models;

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
}
