<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swap_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swap_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 3)->default('eur');

            $table->string('status')->default('pending');
            // pending, reserved, captured, released, failed

            $table->text('failure_reason')->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->timestamp('released_at')->nullable();

            $table->timestamps();

            $table->unique(['swap_request_id', 'user_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swap_payments');
    }
};
