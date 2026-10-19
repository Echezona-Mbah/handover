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
    ];

    public function nurses()
    {
        return $this->hasMany(Nurses::class, 'ward_id');
    }

    protected $table ='wards';

    protected $primaryKey ='id';
}
