<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Nurses extends Authenticatable
{
    use Notifiable;

    protected $table = 'nurses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'license_number', 
        'ages', 
        'gender', 
        'qualification', 
        'blood_group', 
        'address', 
        'city', 
        'state', 
        'hospital_id', 
        'shift_schedule_id', 
        'status', 
        'profile_image', 
        'password', 
        'ward_id',
        'nurse_department_id',
        'nursing_role_id',
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
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function ShiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'shift_schedules_id');
    }

    public function NursingRole()
    {
        return $this->belongsTo(NursingRole::class, 'nursing_role_id');
    }

    public function Nursinghandover()
    {
        return $this->belongsTo(NurseHandover::class, 'nurse_handovers_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function NurseDepartment()
    {
        return $this->belongsTo(NurseDepartment::class,'nurse_department_id');
    }
}
