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
        Schema::create('group_members', function (Blueprint $table) {
            $table->ulid('id')->primary();

    $table->foreignUlid('group_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignUlid('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('role', [
        'owner',
        'admin',
        'editor',
        'viewer'
    ])->default('viewer');

    $table->enum('status', [
        'pending',
        'accepted',
        'declined'
    ])->default('pending');

    $table->timestamp('joined_at')->nullable();

    

    $table->unique(['group_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
