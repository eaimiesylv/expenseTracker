<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurrence_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->string('frequency_type');
            $table->unsignedSmallInteger('interval_value')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->dateTime('next_occurrence_at')->nullable();
            $table->dateTime('last_generated_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_generate')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['subject_type', 'subject_id']);
            $table->index(['is_active', 'next_occurrence_at']);
            $table->index(['frequency_type', 'interval_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurrence_rules');
    }
};
