<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorNotification extends Model
{
      protected $fillable = [
        'department_id',
        'hospital_id',
        'doctor_unit_id',
        'doctor_sub_unit_id',
        'patient_id',
        'title',
        'message',
        'type',
        'seen_at',
    ];

    protected $casts = [
        'seen_at' => 'datetime',
    ];
}
