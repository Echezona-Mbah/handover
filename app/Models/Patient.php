<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str; // optional if you use uuid later


class Patient extends Model
{
    protected $fillable = [
        'hospital_id',
        'doctor_id',
        'nurse_id',
        'doctor_unit_id',
        'doctor_sub_unit_id',
        'dentist_unit_id',
        'patient_number',
        'full_name',
        'dob',
        'gender',
        'email',
        'mobile_number',
        'marital_status',
        'occupation', 
        'blood_group', 
        'blood_pressure', 
        'sugar_level', 
        'address', 
        'city', 
        'state', 
        'doctor_assign',
        'admin_assign_doctor',
        'dentist_assign',
        'admin_assign_dentist',
        'current_condition',
        'ward_id',
        'current_medications',
        'allergies',
        'urgency_status',
        'status', 
        'discharge_notes',
         'discharged_at',
         'pt_number',
         'pending_transfer_unit_id',
        'pending_transfer_sub_unit_id',
        'transfer_from_unit_id',
        'transfer_from_sub_unit_id',
        'transfer_status',

    ];


public static function generatePtNumber(): string
{
    do {
        $pt = 'PT' . now()->format('Ymd') . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    } while (self::where('pt_number', $pt)->exists());

    return $pt;
}


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

    // Relationship with Department
    public function doctorDepartment()
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_departments');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

public function doctorUnit()
{
    return $this->belongsTo(DoctorUnit::class, 'doctor_unit_id');
}

public function doctorSubUnit()
{
    return $this->belongsTo(DoctorSubUnit::class, 'doctor_sub_unit_id');
}


    public function dentistUnit()
{
    return $this->belongsTo(DentistUnit::class, 'dentist_unit_id');
}



    protected $table ='patients';

    protected $primaryKey ='id';
}
