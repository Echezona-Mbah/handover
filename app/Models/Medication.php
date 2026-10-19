<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'hospital_id',
        'doctor_id',
        'dentist_id',
        'patient_id',
        'nurse_id',
        'medication',
        'dosage',
        'instructions',
        'frequency',
        'start_date',
        'end_date',

    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');

    }

    public function doctorById()
    {
        return $this->belongsTo(Doctor::class, 'doctor_assign');
    }

    public function nurses()
    {
        return $this->belongsTo(Nurses::class, 'nurses_id');
    }

    public function dentist()
    {
        return $this->belongsTo(Dentists::class, 'dentist_id');
    }

    // Relationship with Department
    public function doctorDepartment()
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_departments');
    }

    protected $table = 'medications';

    protected $primaryKey = 'id';
}
