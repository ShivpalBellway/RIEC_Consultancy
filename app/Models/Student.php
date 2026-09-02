<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'passport_number',
        'date_of_birth',
        'gender',
        'nationality',
        'korean_address',
        'korean_city',
        'korean_postal_code',
        'korean_contact_number',
        'university_name',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function mandatoryDocumentsUploadedCount(): int
    {
        return $this->documents()
            ->where('is_mandatory', true)
            ->whereIn('status', ['uploaded', 'verified'])
            ->count();
    }
}
