<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorActivity extends Model
{
    protected $fillable = [
        'doctor_id',
        'hospital_id',
        'action',
        'route',
        'method',
        'url',
        'ip',
        'user_agent',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
