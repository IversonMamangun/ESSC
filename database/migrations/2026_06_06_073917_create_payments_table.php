<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->restrictOnDelete();
            $table->string('status')->default(PaymentStatus::PENDING->value);
            $table->date('payment_date')->nullable();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('cancelled_amount')->default(0);
            
            $table->string('gateway')->nullable();
            $table->string('gateway_payment_intent_id')->nullable()->index();
            $table->string('gateway_payment_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
