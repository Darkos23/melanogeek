<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->string('currency', 10);
            $table->enum('status', [
                'pending',      // commande soumise, en attente d'acceptation
                'accepted',     // créateur a accepté
                'in_progress',  // en cours de réalisation
                'delivered',    // créateur a livré
                'completed',    // acheteur a confirmé
                'cancelled',    // annulée
            ])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('payment_method')->nullable();   // wave, orange_money, stripe
            $table->string('transaction_id')->nullable();
            $table->text('requirements')->nullable();       // instructions de l'acheteur
            $table->text('seller_note')->nullable();        // note de livraison
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
