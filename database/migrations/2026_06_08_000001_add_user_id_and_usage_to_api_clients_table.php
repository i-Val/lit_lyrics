<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_clients', function (Blueprint $table) {
            if (! Schema::hasColumn('api_clients', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('api_clients', 'requests_count')) {
                $table->unsignedBigInteger('requests_count')->default(0)->after('last_used_ip');
            }

            if (! Schema::hasColumn('api_clients', 'api_key_created_at')) {
                $table->timestamp('api_key_created_at')->nullable()->after('api_key_hash');
            }
        });

        DB::table('api_clients')
            ->whereNull('user_id')
            ->whereNotNull('email')
            ->update([
                'user_id' => DB::raw('(select u.id from users u where u.email = api_clients.email limit 1)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('api_clients', function (Blueprint $table) {
            if (Schema::hasColumn('api_clients', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('api_clients', 'requests_count')) {
                $table->dropColumn('requests_count');
            }
            if (Schema::hasColumn('api_clients', 'api_key_created_at')) {
                $table->dropColumn('api_key_created_at');
            }
        });
    }
};

