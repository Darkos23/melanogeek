<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category');               // photo, music, design, fashion, video, writing, coaching, other
            $table->decimal('price', 10, 2);
            $table->string('currency', 10)->default('XOF');
            $table->integer('delivery_days')->default(7);
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('orders_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
