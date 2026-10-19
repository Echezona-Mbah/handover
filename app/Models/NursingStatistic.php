<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingStatistic extends Model
{
    protected $fillable = [
        'hospital_id', 'ward_id', 'nurse_id', 'stats_date', 'admissions',
        'discharges', 'transfers', 'deaths', 'bed_occupied', 'bed_vacant',
        'clinic_attendance', 'specialized_service_count', 'notes',
        'verified_by', 'verified_at',
    ];

    protected $casts = [
        'stats_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function nurse()
    {
        return $this->belongsTo(Nurses::class, 'nurse_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}

