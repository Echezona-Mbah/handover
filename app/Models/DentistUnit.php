<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentistUnit extends Model
{
    use HasFactory;

    protected $table = 'dentist_units';

    protected $fillable = [
        'name',
        'hospital_id',
    ];

    // If you want to link it to a hospital:
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
