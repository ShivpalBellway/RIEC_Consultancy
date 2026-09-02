<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = [
        'title', 'description', 'image',
        'hero_image', 'hero_badge', 'hero_heading_line1', 'hero_heading_highlight', 'hero_subtext',
    ];

    public static function getContent()
    {
        return self::first() ?? new self();
    }
}
