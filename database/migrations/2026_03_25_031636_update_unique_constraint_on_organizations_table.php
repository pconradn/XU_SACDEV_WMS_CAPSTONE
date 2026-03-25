<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {

            if (!Schema::hasColumn('organizations', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('updated_at');
            }
        });

        DB::statement("
            SET @index_name := (
                SELECT INDEX_NAME
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'organizations'
                AND COLUMN_NAME = 'name'
                AND NON_UNIQUE = 0
                LIMIT 1
            );
        ");

        DB::statement("
            SET @sql := IF(@index_name IS NOT NULL,
                CONCAT('ALTER TABLE organizations DROP INDEX ', @index_name),
                'SELECT 1'
            );
        ");

        DB::statement("PREPARE stmt FROM @sql");
        DB::statement("EXECUTE stmt");
        DB::statement("DEALLOCATE PREPARE stmt");

        Schema::table('organizations', function (Blueprint $table) {
            $table->unique(['name', 'archived_at'], 'org_name_archived_unique');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {

            $table->dropUnique('org_name_archived_unique');

            $table->unique('name');

            $table->dropColumn('archived_at');
        });
    }
};