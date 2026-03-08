<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('country_type');
            $table->string('creator_category')->nullable()->after('status');
            $table->text('creator_bio')->nullable()->after('creator_category');
            $table->json('creator_socials')->nullable()->after('creator_bio');
            $table->text('rejection_reason')->nullable()->after('creator_socials');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'creator_category', 'creator_bio', 'creator_socials', 'rejection_reason', 'approved_at']);
        });
    }
};
