<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('what_you_learn')->nullable();   // bullet points JSON
            $table->text('requirements')->nullable();     // bullet points JSON

            $table->enum('category', [
                'reseaux_securite',
                'informatique_gestion',
                'bases_informatiques',
            ]);
            $table->enum('level', ['debutant', 'intermediaire', 'avance'])->default('debutant');
            $table->enum('language', ['fr', 'en', 'wo'])->default('fr');

            $table->string('thumbnail')->nullable();
            $table->string('preview_video')->nullable();  // vidéo de présentation gratuite

            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('XOF');
            $table->boolean('is_free')->default(false);

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->unsignedInteger('total_duration')->default(0); // en minutes
            $table->unsignedInteger('total_lessons')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
