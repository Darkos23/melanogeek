<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();

            $table->enum('payment_method', ['wave', 'orange_money', 'free', 'subscription'])->default('free');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('XOF');
            $table->string('transaction_id')->nullable();

            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->timestamp('expires_at')->nullable(); // null = accès à vie

            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
