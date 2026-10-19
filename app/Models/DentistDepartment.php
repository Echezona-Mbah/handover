<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DentistDepartment extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function dentist()
    {
        return $this->hasMany(Dentists::class, 'dentist_department_id');
    }

    protected $table ='dentist_departments';

    protected $primaryKey ='id';
}