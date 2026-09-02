<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('consent_accepted')->default(false)->after('form_answers');
            $table->text('consent_text')->nullable()->after('consent_accepted');
            $table->timestamp('consent_accepted_at')->nullable()->after('consent_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'consent_accepted',
                'consent_text',
                'consent_accepted_at',
            ]);
        });
    }
};
