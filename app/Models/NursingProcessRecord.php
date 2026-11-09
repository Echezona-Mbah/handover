<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingProcessRecord extends Model
{
    protected $fillable = [
        'hospital_id',
        'ward_id',
        'patient_id',
        'nurse_id',
        'assessment',
        'diagnosis',
        'planning',
        'implementation',
        'evaluation',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function nurse()
    {
        return $this->belongsTo(Nurses::class, 'nurse_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
}
