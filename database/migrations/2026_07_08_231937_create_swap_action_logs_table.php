<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swap_action_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swap_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('event');
            // requested, approved_by_worker, declined_by_worker, employers_notified,
            // employer_approved, employer_declined, payment_reserved, payment_captured,
            // payment_released, payment_failed, confirmed, cancelled

            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index('event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swap_action_logs');
    }
};
