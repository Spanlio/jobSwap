<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Public, anonymous fields
            $table->string('current_job_title');
            $table->string('desired_job_title');
            $table->text('licenses')->nullable();
            $table->unsignedTinyInteger('years_experience');
            $table->string('region');
            $table->string('availability');

            // Private fields, never shown publicly - used to notify the employer
            $table->string('employer_email');
            $table->string('employer_name')->nullable();

            $table->string('status')->default('active');
            // active, swapped, removed, expired

            $table->timestamp('expires_at');
            $table->timestamp('expiry_reminder_sent_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'region']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
