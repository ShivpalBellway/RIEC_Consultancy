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
        if (Schema::hasTable('student_documents')) {
            return;
        }

        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('document_type'); // e.g. passport, academic_transcript, ielts_pte, financial_statement, photo, offer_letter
            $table->string('document_name');
            $table->string('file_path');
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->enum('status', ['pending', 'uploaded', 'verified', 'rejected', 're_upload_required'])->default('uploaded');
            $table->text('admin_comment')->nullable();

            // Removal Request Workflow
            $table->enum('removal_request_status', ['none', 'requested', 'approved', 'rejected'])->default('none');
            $table->text('removal_request_reason')->nullable();
            $table->timestamp('removal_requested_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
