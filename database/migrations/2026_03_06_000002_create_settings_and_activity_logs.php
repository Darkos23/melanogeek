<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Paramètres plateforme ──
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Valeurs par défaut
        DB::table('settings')->insert([
            ['key' => 'maintenance_mode',        'value' => '0',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_announcement',        'value' => null,   'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_announcement_type',   'value' => 'info', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'allow_registrations',      'value' => '1',    'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Logs d'activité staff ──
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
    }
};
