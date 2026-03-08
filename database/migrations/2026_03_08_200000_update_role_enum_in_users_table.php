<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter 'creator' à l'ENUM existant ['user', 'admin', 'owner']
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('user','creator','admin','owner') DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('user','admin','owner') DEFAULT 'user'");
    }
};
