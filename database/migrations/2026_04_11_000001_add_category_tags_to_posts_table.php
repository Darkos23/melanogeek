<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('category', [
                'manga-anime',
                'gaming',
                'tech',
                'cinema-series',
                'culture',
                'debat',
            ])->nullable()->after('title');

            $table->json('tags')->nullable()->after('category');
        });
    }

    public function down(): void {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['category', 'tags']);
        });
    }
};
