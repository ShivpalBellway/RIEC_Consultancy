<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eligibility_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('label');                     // "Minimum Age", "SEE GPA", etc.
            $table->string('field_key')->nullable();     // machine-readable: "min_age", "see_gpa"
            $table->string('field_type')->default('number'); // text, number, date, select, checkbox, file
            $table->boolean('is_required')->default(true);
            $table->string('min_value')->nullable();     // e.g. 17 for age, 2.0 for GPA
            $table->string('max_value')->nullable();
            $table->json('options')->nullable();         // for select: ["Level 1","Level 2",...]
            $table->string('placeholder')->nullable();
            $table->string('validation_message')->nullable(); // custom error message shown to student
            $table->string('unit')->nullable();          // "years", "GPA points", etc.
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eligibility_fields');
    }
};
