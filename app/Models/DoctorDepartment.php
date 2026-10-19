<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDepartment extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'doctor_departments');
    }

    public function units()
    {
        return $this->hasMany(DoctorUnit::class);
    }

    protected $table ='doctor_departments';

    protected $primaryKey ='id';
}
