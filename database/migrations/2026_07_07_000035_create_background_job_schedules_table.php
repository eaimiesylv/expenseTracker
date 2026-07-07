<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('background_job_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('job_key')->unique();
            $table->string('job_type');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('scheduled_at');
            $table->dateTime('next_run_at')->nullable();
            $table->dateTime('last_started_at')->nullable();
            $table->dateTime('last_finished_at')->nullable();
            $table->unsignedInteger('attempt_count')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'scheduled_at']);
            $table->index(['job_type', 'status']);
            $table->index(['next_run_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('background_job_schedules');
    }
};
