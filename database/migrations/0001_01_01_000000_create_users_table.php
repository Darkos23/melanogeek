<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ── Identité ──
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // ── Profil ──
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('niche')->nullable();        // ex: Photographe, Musicien
            $table->string('location')->nullable();     // ex: Dakar, Sénégal
            $table->string('website')->nullable();

            // ── Réseaux sociaux ──
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();

            // ── Plan & Origine ──
            $table->enum('country_type', ['senegal', 'africa', 'international'])->default('senegal');
            $table->enum('plan', ['free', 'african', 'global'])->default('free');
            $table->timestamp('plan_expires_at')->nullable();

            // ── Statut ──
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);

            // ── Paiement mobile ──
            $table->string('wave_number')->nullable();
            $table->string('orange_money_number')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};