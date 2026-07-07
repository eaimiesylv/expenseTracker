<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_role_id')->constrained('group_roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('permission_key');
            $table->boolean('granted')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['group_role_id', 'permission_key']);
            $table->index(['permission_key', 'granted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_role_permissions');
    }
};
