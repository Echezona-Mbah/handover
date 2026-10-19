<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseDepartment extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];



    protected $table ='nurse_departments';

    protected $primaryKey ='id';
}
