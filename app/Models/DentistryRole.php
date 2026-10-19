<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DentistryRole extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    protected $table ='dentist_roles';

    protected $primaryKey ='id';
}
