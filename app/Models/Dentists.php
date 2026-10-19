<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dentists extends Authenticatable
{
    use Notifiable;
    protected $guard = 'dentist';


    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'ages',
        'gender',
        'dentist_unit_id', 
        'dentist_role_id', 
        'dentist_department_id',
        'license_number', 
        'years_of_experience', 
        'hospital_id', 
        'shift_schedule_id', 
        'status', 
        'qualification',
        'blood_group',
        'address',
        'city',
        'state',
        'profile_image', 
        'password', 
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
    ];

    public function hospitals()
    {
        return $this->belongsTo(Hospital::class,'hospital_id');
    }

    public function shiftSchedules()
    {
        return $this->hasMany(\App\Models\ShiftSchedule::class, 'dentist_id');
    }

    // Relationship with Department
    public function DentistRole()
    {
        return $this->belongsTo(DentistRole::class, 'dentist_role_id');
    }

    public function DentistDepartment()
    {
        return $this->belongsTo(DentistDepartment::class, 'dentist_department_id');
    }

    
    public function DentistUnit()
    {
        return $this->belongsTo(DentistUnit::class, 'dentist_unit_id');
    }

    

    protected $table ='dentists';

    protected $primaryKey ='id';
}
