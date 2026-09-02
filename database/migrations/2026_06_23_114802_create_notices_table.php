<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->enum('type', ['general', 'important', 'urgent', 'event', 'update'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->json('file_paths')->nullable();
            $table->json('file_names')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('priority');
            $table->index('is_pinned');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
};
