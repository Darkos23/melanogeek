<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 50);
            $table->string('title', 200);
            $table->text('body');
            $table->boolean('is_pinned')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category', 'created_at']);
            $table->index('last_reply_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_threads');
    }
};
