<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EligibilityField extends Model
{
    protected $fillable = [
        'program_id', 'label', 'field_key', 'field_type',
        'is_required', 'min_value', 'max_value', 'options',
        'placeholder', 'validation_message', 'unit', 'is_active', 'sort_order', 'store_in_system'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
        'options'     => 'array',
        'store_in_system' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public static function fieldTypes(): array
    {
        return [
            'number'   => 'Number',
            'text'     => 'Text',
            'date'     => 'Date',
            'select'   => 'Dropdown (Select)',
            'checkbox' => 'Checkbox (Yes/No)',
            'file'     => 'File Upload',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
