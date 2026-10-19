<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DentistRole extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
    public function dentist()
    {
        return $this->hasMany(Dentists::class, 'dentist_role_id');
    }


    protected $table ='dentistry_roles';

    protected $primaryKey ='id';
}
