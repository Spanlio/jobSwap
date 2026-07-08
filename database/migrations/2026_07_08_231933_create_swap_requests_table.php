<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swap_requests', function (Blueprint $table) {
            $table->id();

            // Worker A - the post owner being requested
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_owner_id')->constrained('users')->cascadeOnDelete();

            // Worker B - the requester, offering their own post in the swap
            $table->foreignId('requester_post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('conversation_id')->nullable()->constrained()->nullOnDelete();

            $table->string('status')->default('pending');
            // pending, declined_by_worker, awaiting_employers, declined_by_employer,
            // confirmed, payment_failed, cancelled

            $table->timestamp('worker_responded_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('cancel_reason')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swap_requests');
    }
};
