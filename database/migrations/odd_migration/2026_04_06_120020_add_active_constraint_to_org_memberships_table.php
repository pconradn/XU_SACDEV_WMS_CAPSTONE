<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('org_memberships', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('archived_at');
        });

     
        DB::statement("
            UPDATE org_memberships
            SET is_active = CASE 
                WHEN archived_at IS NULL THEN 1 
                ELSE 0 
            END
        ");


        DB::statement("
            UPDATE org_memberships om
            JOIN (
                SELECT organization_id, school_year_id, role,
                       MAX(id) as keep_id
                FROM org_memberships
                WHERE is_active = 1
                GROUP BY organization_id, school_year_id, role
                HAVING COUNT(*) > 1
            ) dup
            ON om.organization_id = dup.organization_id
            AND om.school_year_id = dup.school_year_id
            AND om.role = dup.role
            SET om.is_active = 0,
                om.archived_at = NOW()
            WHERE om.id != dup.keep_id
        ");

        Schema::table('org_memberships', function (Blueprint $table) {
            $table->unique(
                ['organization_id', 'school_year_id', 'role', 'is_active'],
                'org_memberships_active_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('org_memberships', function (Blueprint $table) {
            $table->dropUnique('org_memberships_active_unique');
        });

        DB::statement("
            UPDATE org_memberships om
            JOIN (
                SELECT organization_id, school_year_id, role,
                       MAX(id) as keep_id
                FROM org_memberships
                WHERE is_active = 1
                GROUP BY organization_id, school_year_id, role
                HAVING COUNT(*) > 1
            ) dup
            ON om.organization_id = dup.organization_id
            AND om.school_year_id = dup.school_year_id
            AND om.role = dup.role
            SET om.archived_at = NOW()
            WHERE om.id != dup.keep_id
        ");

        Schema::table('org_memberships', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};