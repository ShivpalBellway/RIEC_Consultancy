<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Agent extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'agency_name',
        'email',
        'password',
        'phone',
        'country',
        'address',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function notifications()
    {
        return $this->hasMany(AgentNotification::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }
}
