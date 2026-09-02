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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('consent_collection')->default(false)->after('remember_token');
            $table->boolean('consent_third_party')->default(false)->after('consent_collection');
            $table->boolean('consent_email_updates')->default(false)->after('consent_third_party');
            $table->boolean('consent_marketing')->default(false)->after('consent_email_updates');
            $table->timestamp('consents_accepted_at')->nullable()->after('consent_marketing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'consent_collection',
                'consent_third_party',
                'consent_email_updates',
                'consent_marketing',
                'consents_accepted_at',
            ]);
        });
    }
};
