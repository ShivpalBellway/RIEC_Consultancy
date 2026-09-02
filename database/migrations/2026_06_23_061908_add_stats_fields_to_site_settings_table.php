<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Stats section left side text
            $table->string('stats_badge')->nullable()->default('WHY CHOOSE REIAC?');
            $table->string('stats_heading_line1')->nullable()->default('Trusted Guidance.');
            $table->string('stats_heading_line2')->nullable()->default('Proven');
            $table->string('stats_heading_highlight')->nullable()->default('Results.');
            $table->text('stats_subtext')->nullable()->default('We are committed to providing transparent, reliable and result-oriented services to help students and institutions achieve their goals.');

            // 2 manual stats (countries + satisfaction)
            $table->string('stat_countries')->nullable()->default('15+');
            $table->string('stat_satisfaction')->nullable()->default('98%');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'stats_badge', 'stats_heading_line1', 'stats_heading_line2',
                'stats_heading_highlight', 'stats_subtext',
                'stat_countries', 'stat_satisfaction',
            ]);
        });
    }
};
