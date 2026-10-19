<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseHandover extends Model
{
    protected $fillable = [
        'hospital_id',
        'nurse_id',
        'patient_id',
        'ward_id',
        'summary',
        'alerts',
        'pending_tasks',

    ];
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
    

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id');

    }
    public function doctorById()
    {
        return $this->belongsTo(Doctor::class, 'doctor_assign');
    }
    

    public function nurses()
    {
        return $this->belongsTo(Nurses::class,'nurses_id');
    }

        public function dentists()
    {
        return $this->belongsTo(Dentists::class,'dentists_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    protected $table ='nurse_handovers';

    protected $primaryKey ='id';
}
