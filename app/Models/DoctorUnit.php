<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'doctor_department_id'];

    // public function subUnits()
    // {
    //     return $this->hasMany(DoctorUnit::class, 'parent_id');
    // }

    public function subUnits()
{
    return $this->hasMany(DoctorSubUnit::class, 'doctor_unit_id');
}

    public function parent()
    {
        return $this->belongsTo(DoctorUnit::class, 'parent_id');
    }

    public function doctorDepartment()
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_department_id');
    }
}