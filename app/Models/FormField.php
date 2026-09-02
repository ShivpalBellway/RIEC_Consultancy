<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $fillable = [
        'section_id', 'label', 'field_key', 'field_type',
        'is_required', 'options', 'placeholder',
        'validation_message', 'is_active', 'sort_order', 'store_in_system'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
        'options'     => 'array',
        'store_in_system' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(FormSection::class, 'section_id');
    }

    public static function fieldTypes(): array
    {
        return [
            'text'     => 'Text Input',
            'number'   => 'Number Input',
            'email'    => 'Email Input',
            'phone'    => 'Phone Number',
            'date'     => 'Date Picker',
            'select'   => 'Dropdown (Select)',
            'textarea' => 'Textarea (Long Text)',
            'checkbox' => 'Checkbox (Yes/No)',
            'file'     => 'File Upload',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
