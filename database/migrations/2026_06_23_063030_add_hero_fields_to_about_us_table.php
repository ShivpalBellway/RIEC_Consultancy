<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->string('hero_image')->nullable();
            $table->string('hero_badge')->nullable()->default('About REIAC');
            $table->string('hero_heading_line1')->nullable()->default('Empowering Futures');
            $table->string('hero_heading_highlight')->nullable()->default('Worldwide.');
            $table->text('hero_subtext')->nullable()->default('REIAC is your trusted global education partner, committed to guiding students towards world-class opportunities and brighter tomorrows.');
        });
    }

    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'hero_badge', 'hero_heading_line1', 'hero_heading_highlight', 'hero_subtext']);
        });
    }
};
