<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_pool_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained('savings_pools')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('contributor_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('amount')->default(0);
            $table->date('contribution_date');
            $table->unsignedInteger('cycle')->default(1);
            $table->string('payment_reference')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pool_id', 'status']);
            $table->index(['contributor_id', 'status']);
            $table->index(['contribution_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_pool_contributions');
    }
};
