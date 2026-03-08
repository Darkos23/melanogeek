<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Modifier l'enum role pour inclure 'creator'
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'creator', 'admin', 'owner') DEFAULT 'user'");

        // 2. Créer la table des demandes creator
        Schema::create('creator_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('motivation');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_requests');
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'admin', 'owner') DEFAULT 'user'");
    }
};
