<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'agent_id',
        'document_type',
        'document_name',
        'file_path',
        'file_size',
        'mime_type',
        'is_mandatory',
        'status',
        'admin_comment',
        'removal_request_status',
        'removal_request_reason',
        'removal_requested_at',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'removal_requested_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public static function mandatoryDocumentTypes(): array
    {
        return self::agentMandatoryDocumentTypes();
    }

    public static function agentMandatoryDocumentTypes(): array
    {
        return [
            'passport'            => 'Passport',
            'academic_transcript' => 'Academic Transcript',
            'ielts_pte'          => 'IELTS/PTE Result',
            'financial_statement' => 'Financial Statement',
            'photo'               => 'Passport Size Photo',
            // 'offer_letter' => 'Offer Letter', // Uploaded by Admin now, commented out for Agent upload
        ];
    }

    public static function allDocumentTypes(): array
    {
        return [
            'passport'            => 'Passport',
            'academic_transcript' => 'Academic Transcript',
            'ielts_pte'          => 'IELTS/PTE Result',
            'financial_statement' => 'Financial Statement',
            'photo'               => 'Passport Size Photo',
            'offer_letter'        => 'Official Offer Letter (Admin Uploaded)',
        ];
    }

    public function getDocumentTypeNameAttribute(): string
    {
        $types = self::allDocumentTypes();
        return $types[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type));
    }
}
