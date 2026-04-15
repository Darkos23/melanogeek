<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['niche', 'niche_wolof', 'wave_number', 'orange_money_number']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('niche')->nullable();
            $table->string('niche_wolof')->nullable();
            $table->string('wave_number')->nullable();
            $table->string('orange_money_number')->nullable();
        });
    }
};
