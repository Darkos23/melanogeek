<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path')->nullable();         // chemin local sur serveur
            $table->unsignedInteger('duration')->default(0); // en minutes
            $table->string('attachment')->nullable();         // fichier PDF/ressource
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_preview')->default(false);   // visible sans inscription
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
