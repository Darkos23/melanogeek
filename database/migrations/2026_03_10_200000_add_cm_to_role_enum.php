<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','creator','cm','admin','owner') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // Rétrograde les CM en user avant de supprimer la valeur
        DB::statement("UPDATE users SET role = 'user' WHERE role = 'cm'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','creator','admin','owner') NOT NULL DEFAULT 'user'");
    }
};
