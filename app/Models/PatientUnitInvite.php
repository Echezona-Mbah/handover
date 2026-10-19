<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PatientUnitInvite extends Model
{
   use HasFactory;

    protected $fillable = [
        'patient_id',
        'department_id',
        'doctor_unit_id',
        'doctor_sub_unit_id',
        'doctor_id',
        'instructions',
        'hospital_id',
        'status',
        'responded_by_doctor_id',
        'responded_at'
    ];

       public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
    

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id');

    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

 
    public function doctorDepartment()
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_departments');
    }

    public function doctorUnit()
    {
        return $this->belongsTo(DoctorUnit::class, 'doctor_unit_id');
    }

    public function doctorSubUnit()
    {
        return $this->belongsTo(DoctorSubUnit::class, 'doctor_sub_unit_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

  
}
