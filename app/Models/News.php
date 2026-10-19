<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
       protected $guard = 'news';


    protected $fillable = [
        'title',
        'content',
        'image', 
        'status', 
        'user_id', 
        'hospital_id', 

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

    protected $table ='news';

    protected $primaryKey ='id';
}
