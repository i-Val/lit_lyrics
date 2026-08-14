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
        try {
            Schema::table('songs', function (Blueprint $table) {
                 // Pass the columns as an array, Laravel determines the index name natively
                $table->dropUnique(['title', 'author']); 
            });
        } catch (\Throwable $e) {
            // Ignore error if index doesn't exist (e.g. SQLite test environments)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('songs', function (Blueprint $table) {
                 // Re-add it if you ever need to roll back the migration
                $table->unique(['title', 'author']); 
            });
        } catch (\Throwable $e) {
            // Ignore error
        }
    }
};
