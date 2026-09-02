<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'name',
        'email',
        'phone',
        'eligibility_answers',
        'form_answers',
        'consent_accepted',
        'consent_text',
        'consent_accepted_at',
        'status',
    ];

    protected $casts = [
        'eligibility_answers' => 'array',
        'form_answers'        => 'array',
        'consent_accepted'    => 'boolean',
        'consent_accepted_at' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function storedAttachment(string $fieldKey): ?array
    {
        $answer = ($this->form_answers ?: [])[$fieldKey] ?? null;

        if (!is_array($answer)
            || empty($answer['is_file'])
            || empty($answer['store_in_system'])
            || !is_string($answer['value'] ?? null)) {
            return null;
        }

        $path = str_replace('\\', '/', ltrim($answer['value'], '/\\'));

        if (!str_starts_with($path, 'applications/attachments/')
            || str_contains($path, '../')) {
            return null;
        }

        $originalName = (string) ($answer['original_name'] ?? basename($path));
        $originalName = basename(str_replace('\\', '/', $originalName));
        $originalName = trim((string) preg_replace('/[\r\n]+/', '', $originalName));

        return [
            'path' => $path,
            'name' => $originalName !== '' ? $originalName : 'attachment',
        ];
    }
}
