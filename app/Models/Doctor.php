<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'specialization', 
        'doctor_departments', 
        'doctor_cardie', 
        'years_of_experience', 
        'hospital_id', 
        'doctor_unit_id',
        'doctor_sub_unit_id',
        'shift_schedule_id', 
        'cadre',
        'status', 
        'profile_image', 
        'password', 
        'ages', 
        'gender', 
        'marital_status', 
        'qualification', 
        'blood_group', 
        'address', 
        'country', 
        'state', 
        'city', 
        'sun_start',
        'sun_end',
        'mon_start',
        'mon_end',
        'tue_start',
        'tue_end',
        'wed_start',
        'wed_end',
        'thu_start',
        'thu_end',
        'fri_start',
        'fri_end',
        'sat_start',
        'sat_end',
        'password_reset_token',
        'password_reset_expires_at',
        'password_set',
        
    ];

    protected $hidden = ['password', 'remember_token'];

    public function hospitals()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
    public function shiftSchedules()
    {
        return $this->hasOne(ShiftSchedule::class, 'doctor_id');
    }
    

    public function doctorDepartment()
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_departments');
    }

       public function doctorUnit()
    {
        return $this->belongsTo(DoctorUnit::class, 'doctor_unit_id');
    }


    public function subUnit()
    {
        return $this->belongsTo(DoctorSubUnit::class, 'doctor_sub_unit_id');
    }


    public function activities()
{
    return $this->hasMany(\App\Models\DoctorActivity::class);
}



    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    protected $table = 'doctors';
    protected $primaryKey = 'id';
}
