<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ALTER TABLE pour étendre l'ENUM avec 'dev'
        DB::statement("ALTER TABLE posts MODIFY COLUMN category ENUM(
            'manga-anime',
            'gaming',
            'tech',
            'dev',
            'cinema-series',
            'culture',
            'debat'
        ) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE posts MODIFY COLUMN category ENUM(
            'manga-anime',
            'gaming',
            'tech',
            'cinema-series',
            'culture',
            'debat'
        ) NULL");
    }
};
