<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agents')) {
            return;
        }

        Schema::table('agents', function (Blueprint $table): void {
            if (!Schema::hasColumn('agents', 'consent_collection')) {
                $table->boolean('consent_collection')->default(false);
            }
            if (!Schema::hasColumn('agents', 'consent_third_party')) {
                $table->boolean('consent_third_party')->default(false);
            }
            if (!Schema::hasColumn('agents', 'consent_email_updates')) {
                $table->boolean('consent_email_updates')->default(false);
            }
            if (!Schema::hasColumn('agents', 'consent_marketing')) {
                $table->boolean('consent_marketing')->default(false);
            }
            if (!Schema::hasColumn('agents', 'consents_accepted_at')) {
                $table->timestamp('consents_accepted_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('agents')) {
            return;
        }

        $columns = array_filter([
            Schema::hasColumn('agents', 'consent_collection') ? 'consent_collection' : null,
            Schema::hasColumn('agents', 'consent_third_party') ? 'consent_third_party' : null,
            Schema::hasColumn('agents', 'consent_email_updates') ? 'consent_email_updates' : null,
            Schema::hasColumn('agents', 'consent_marketing') ? 'consent_marketing' : null,
            Schema::hasColumn('agents', 'consents_accepted_at') ? 'consents_accepted_at' : null,
        ]);

        if ($columns) {
            Schema::table('agents', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
