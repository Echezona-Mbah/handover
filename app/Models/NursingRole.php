<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingRole extends Model
{
    public const CADRES = [
        'Nursing officer one',
        'Nursing officer two',
        'Assistant chief nursing officer',
        'Chief nursing officer',
        'Assistant Director of Nursing',
        'Deputy Director of Nursing',
    ];

    protected $fillable = [
        'name',
        'description',
    ];

    protected $table = 'nursing_roles';

    protected $primaryKey = 'id';
}
