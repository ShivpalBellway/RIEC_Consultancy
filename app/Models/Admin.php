<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'otp_last_sent_at',
    ];

    protected $hidden = ['password', 'otp_code'];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'otp_attempts' => 'integer',
        'otp_last_sent_at' => 'datetime',
    ];

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
