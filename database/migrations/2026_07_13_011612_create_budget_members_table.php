<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_members', function (Blueprint $table) {
            $table->ulid('id')->primary();

    $table->foreignUlid('budget_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignUlid('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('role', [
        'owner',
        'editor',
        'viewer'
    ])->default('viewer');

    $table->timestamps();

    $table->unique(['budget_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_members');
    }
};
