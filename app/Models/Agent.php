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
        'suspension_reason',
        'email_verified_at',
        'consent_collection',
        'consent_third_party',
        'consent_email_updates',
        'consent_marketing',
        'consents_accepted_at',
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
            'consent_collection' => 'boolean',
            'consent_third_party' => 'boolean',
            'consent_email_updates' => 'boolean',
            'consent_marketing' => 'boolean',
            'consents_accepted_at' => 'datetime',
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
