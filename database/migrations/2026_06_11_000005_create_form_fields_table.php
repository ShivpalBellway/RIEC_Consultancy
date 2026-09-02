<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('form_sections')->cascadeOnDelete();
            $table->string('label');              // "Full Name", "Date of Birth"
            $table->string('field_key')->nullable();
            // text, number, date, select, file, textarea, checkbox, phone, email
            $table->string('field_type')->default('text');
            $table->boolean('is_required')->default(true);
            $table->json('options')->nullable();  // for select/checkbox fields
            $table->string('placeholder')->nullable();
            $table->string('validation_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
