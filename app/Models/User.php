<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Hospital;
use App\Notifications\AdminPasswordResetNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'hospital_id',
        'doctor_department_id',
        'nursing_admin_role',
        'nursing_hierarchy_role',
        'profile_picture',
    ];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new AdminPasswordResetNotification($token));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hospitals()
    {
        return $this->belongsTo(Hospital::class,'hospital_id');
    }
    //     public function news()
    // {
    //     return $this->belongsTo(News::class,'news');
    // }

    public function NursingRole()
    {
        return $this->belongsTo(NursingRole::class, 'nursing_role_id');
    }

    public function Nursinghandover()
    {
        return $this->belongsTo(NurseHandover::class, 'nurse_handovers_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function NurseDepartment()
    {
        return $this->belongsTo(NurseDepartment::class,'nurse_department_id');
    }

    public function doctorDepartment()
{
    return $this->belongsTo(DoctorDepartment::class, 'doctor_department_id');
}

public function isSuperAdmin(): bool
{
    return (bool) $this->is_super_admin;
}

    public function assignedNursingReports()
    {
        return $this->hasMany(NursingReport::class, 'assigned_admin_id');
    }

    protected $table ='users';
    protected $primaryKey ='id';
}
