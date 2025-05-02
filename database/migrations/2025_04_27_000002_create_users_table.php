<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('user_roles')->onDelete('restrict');
            $table->string('full_name', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password', 255)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->enum('status', ['active', 'inactive', 'block'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
