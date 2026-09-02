<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_badge')->nullable()->default('REIAC CONSULTING');
            $table->string('hero_heading_line1')->nullable()->default('Your Global Education');
            $table->string('hero_heading_line2')->nullable()->default('Partner for a');
            $table->string('hero_heading_highlight')->nullable()->default('Better Tomorrow');
            $table->text('hero_subtext')->nullable()->default('We guide students to world-class universities and help institutions build stronger global partnerships.');
            $table->string('hero_btn1_text')->nullable()->default('For Students');
            $table->string('hero_btn2_text')->nullable()->default('For Institutions');
            $table->string('hero_btn2_url')->nullable()->default('#');
            $table->string('hero_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_badge', 'hero_heading_line1', 'hero_heading_line2',
                'hero_heading_highlight', 'hero_subtext',
                'hero_btn1_text', 'hero_btn2_text', 'hero_btn2_url', 'hero_image',
            ]);
        });
    }
};
