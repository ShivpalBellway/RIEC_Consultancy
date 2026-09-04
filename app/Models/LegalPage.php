<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
    ];

    public static function contentFor(string $slug): self
    {
        return self::firstOrCreate(
            ['slug' => $slug],
            ['title' => str_replace('-', ' ', ucwords($slug, '-'))]
        );
    }
}