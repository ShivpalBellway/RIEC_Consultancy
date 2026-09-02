<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'name', 'country', 'program_type', 'image', 'description', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function eligibilityFields(): HasMany
    {
        return $this->hasMany(EligibilityField::class)->orderBy('sort_order');
    }

    public function formSections(): HasMany
    {
        return $this->hasMany(FormSection::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function programTypes(): array
    {
        return [
            'bachelor'  => 'Bachelor / Associate Degree',
            'language'  => 'Language Program',
            'master'    => 'Master Degree',
            'diploma'   => 'Diploma Program',
            'other'     => 'Other',
        ];
    }

    public function getProgramTypeLabelAttribute(): string
    {
        return self::programTypes()[$this->program_type] ?? $this->program_type;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        return asset('storage/' . $this->image);
    }
}
