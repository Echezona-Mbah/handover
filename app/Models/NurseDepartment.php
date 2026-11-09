<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseDepartment extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function wards()
    {
        return $this->hasMany(Ward::class, 'nurse_department_id');
    }


    protected $table ='nurse_departments';

    protected $primaryKey ='id';
}
