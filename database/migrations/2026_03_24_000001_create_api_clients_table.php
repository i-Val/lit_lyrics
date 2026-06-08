<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('api_key_hash', 64)->unique();
            $table->boolean('is_active')->default(true);
            $table->string('subscription_plan')->nullable();
            $table->timestamp('subscription_expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->string('last_used_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
