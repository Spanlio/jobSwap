<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employer_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swap_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('users')->cascadeOnDelete();

            $table->string('role');
            // employer_a (post owner's employer), employer_b (requester's employer)

            $table->string('employer_email');
            $table->string('token', 64)->unique();

            $table->string('status')->default('pending');
            // pending, approved, declined

            $table->text('question')->nullable();
            $table->text('answer')->nullable();

            $table->timestamp('notified_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->string('responded_ip', 45)->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_approvals');
    }
};
