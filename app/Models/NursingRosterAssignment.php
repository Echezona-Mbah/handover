<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingRosterAssignment extends Model
{
    protected $fillable = [
        'nursing_roster_id', 'nurse_id', 'duty_date', 'shift_type',
        'start_time', 'end_time', 'notes',
    ];

    protected $casts = [
        'duty_date' => 'date',
    ];

    public function roster()
    {
        return $this->belongsTo(NursingRoster::class, 'nursing_roster_id');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurses::class, 'nurse_id');
    }
}

