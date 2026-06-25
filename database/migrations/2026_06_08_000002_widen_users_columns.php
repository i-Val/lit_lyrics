<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `users` MODIFY `firstname` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `users` MODIFY `lastname` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN firstname TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE users ALTER COLUMN lastname TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(255)');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `users` MODIFY `firstname` VARCHAR(10) NOT NULL');
            DB::statement('ALTER TABLE `users` MODIFY `lastname` VARCHAR(10) NOT NULL');
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(11) NOT NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN firstname TYPE VARCHAR(10)');
            DB::statement('ALTER TABLE users ALTER COLUMN lastname TYPE VARCHAR(10)');
            DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(11)');
        }
    }
};

