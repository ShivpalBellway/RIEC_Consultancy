<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'header_logo', 'footer_logo',
        'hero_badge', 'hero_heading_line1', 'hero_heading_line2',
        'hero_heading_highlight', 'hero_subtext',
        'hero_btn1_text', 'hero_btn2_text', 'hero_btn2_url', 'hero_image',
        'stats_badge', 'stats_heading_line1', 'stats_heading_line2',
        'stats_heading_highlight', 'stats_subtext',
        'stat_countries', 'stat_satisfaction',
        'contact_hero_image', 'contact_hero_badge', 'contact_hero_heading_line1',
        'contact_hero_heading_highlight', 'contact_hero_subtext',
        'contact_phone', 'contact_hours', 'contact_email',
        'contact_address_en', 'contact_address_ko',
        'social_instagram', 'social_facebook', 'social_linkedin', 'social_youtube',
        'contact_map_embed', 'contact_map_url',
        'application_recipient_email',
    ];

    public static function applicationRecipientEmail(): ?string
    {
        $email = static::query()->value('application_recipient_email')
            ?: config('mail.application_recipient');

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }
}
