<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_permission_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('role_id')->nullable()->constrained('group_roles')->nullOnDelete()->cascadeOnUpdate();
            $table->string('permission_key');
            $table->boolean('granted')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['group_id', 'permission_key']);
            $table->index(['user_id', 'permission_key']);
            $table->index(['role_id', 'permission_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_permission_overrides');
    }
};
