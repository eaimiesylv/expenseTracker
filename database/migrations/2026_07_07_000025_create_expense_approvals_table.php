<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained('expenses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('decision')->default('pending');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['expense_id', 'decision']);
            $table->index(['submitted_by', 'decision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_approvals');
    }
};
