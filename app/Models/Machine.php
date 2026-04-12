<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['machine_code', 'name', 'type', 'is_active', 'is_available'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(MachineLog::class);
    }
}
