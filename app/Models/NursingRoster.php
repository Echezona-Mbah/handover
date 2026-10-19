<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingRoster extends Model
{
    protected $fillable = [
        'hospital_id', 'ward_id', 'created_by', 'title', 'start_date',
        'end_date', 'status', 'submitted_at', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Nurses::class, 'created_by');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function assignments()
    {
        return $this->hasMany(NursingRosterAssignment::class);
    }
}

