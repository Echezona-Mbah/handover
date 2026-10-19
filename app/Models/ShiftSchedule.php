<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $fillable = [
        'hospital_id',
        'doctor_id',
        'dentist_id',
        'nurse_id',
        'date',
        'start_time',
        'end_time', 
        'shift_type', 
        'doctor_departments', 
        'status',
        'sun_start', 'sun_end',
        'mon_start', 'mon_end',
        'tue_start', 'tue_end',
        'wed_start', 'wed_end',
        'thu_start', 'thu_end',
        'fri_start', 'fri_end',
        'sat_start', 'sat_end',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
    

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id');
    }

    public function nurses()
    {
        return $this->belongsTo(Nurses::class,'nurses_id');
    }

        public function dentist()
    {
        return $this->belongsTo(Dentists::class,'dentist_id');
    }

    // Relationship with Department
    public function doctorDepartment()
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_departments');
    }


    protected $table ='shift_schedules';

    protected $primaryKey ='id';
}
