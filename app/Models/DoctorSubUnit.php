<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSubUnit extends Model
{
    protected $fillable = ['doctor_unit_id', 'name'];

    public function unit()
    {
        return $this->belongsTo(DoctorUnit::class);
    }
       public function doctors()
    {
        return $this->hasMany(Doctor::class, 'doctor_sub_unit_id');
    }
}
