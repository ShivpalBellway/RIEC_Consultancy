<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Hero
            $table->string('contact_hero_image')->nullable();
            $table->string('contact_hero_badge')->nullable()->default('CONTACT US');
            $table->string('contact_hero_heading_line1')->nullable()->default("We'd Love to");
            $table->string('contact_hero_heading_highlight')->nullable()->default('Hear From You!');
            $table->text('contact_hero_subtext')->nullable()->default('Have questions about studying abroad? Our team is here to help you every step of the way.');

            // Contact Info
            $table->string('contact_phone')->nullable()->default('+82 10-6552-8264');
            $table->string('contact_hours')->nullable()->default('Mon - Sun: 9:00 AM - 8:00 PM (KST)');
            $table->string('contact_email')->nullable()->default('application.reiac@gmail.com');
            $table->text('contact_address_en')->nullable()->default("3rd Floor Room No. 305,\n118 Sujeong-ro, Sujeong-gu,\nSeongnam-si, Gyeonggi-do");
            $table->string('contact_address_ko')->nullable()->default('경기도 성남시 수정구 수정로 118. 3층 305호');

            // Social
            $table->string('social_instagram')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();

            // Map
            $table->text('contact_map_embed')->nullable();
            $table->string('contact_map_url')->nullable()->default('https://maps.google.com/?q=118+Sujeong-ro,+Sujeong-gu,+Seongnam-si,+Gyeonggi-do');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_hero_image','contact_hero_badge','contact_hero_heading_line1',
                'contact_hero_heading_highlight','contact_hero_subtext',
                'contact_phone','contact_hours','contact_email',
                'contact_address_en','contact_address_ko',
                'social_instagram','social_facebook','social_linkedin','social_youtube',
                'contact_map_embed','contact_map_url',
            ]);
        });
    }
};
