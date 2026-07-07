<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('role_id')->nullable()->constrained('group_roles')->nullOnDelete()->cascadeOnUpdate();
            $table->string('membership_status')->default('active');
            $table->dateTime('joined_at')->nullable();
            $table->dateTime('left_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['group_id', 'user_id']);
            $table->index(['group_id', 'membership_status']);
            $table->index(['user_id', 'membership_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
