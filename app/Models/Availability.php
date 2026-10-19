<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;
    protected $fillable = ['doctor_id', 'day', 'start_time', 'end_time'];
 

    protected $table ='availabilities';

    protected $primaryKey ='id';
}
