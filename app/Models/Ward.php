<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'linen_color',
        'hospital_id',
        'unit_head_id',
        'nurse_department_id',
    ];

    public function nurses()
    {
        return $this->hasMany(Nurses::class, 'ward_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'ward_id');
    }

    public function nursingReports()
    {
        return $this->hasMany(NursingReport::class, 'ward_id');
    }

    public function unitHead() { return $this->belongsTo(User::class, 'unit_head_id'); }
    public function hospital() { return $this->belongsTo(Hospital::class, 'hospital_id'); }
    public function nurseDepartment() { return $this->belongsTo(NurseDepartment::class, 'nurse_department_id'); }

    protected $table ='wards';

    protected $primaryKey ='id';
}
