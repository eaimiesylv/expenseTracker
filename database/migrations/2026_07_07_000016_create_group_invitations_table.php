<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('invited_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('role_id')->nullable()->constrained('group_roles')->nullOnDelete()->cascadeOnUpdate();
            $table->string('invitation_token')->unique();
            $table->string('ulid')->unique();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('expires_at');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['group_id', 'status']);
            $table->index(['email', 'status']);
            $table->index(['expires_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_invitations');
    }
};
