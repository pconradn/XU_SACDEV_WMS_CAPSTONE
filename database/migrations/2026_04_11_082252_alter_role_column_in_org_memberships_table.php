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

            // drop UNIQUE constraint first (required)
            $table->dropUnique('org_memberships_active_unique');

        });

        // change ENUM to VARCHAR
        DB::statement("ALTER TABLE org_memberships MODIFY role VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        // revert back to enum (adjust values if needed)
        DB::statement("
            ALTER TABLE org_memberships 
            MODIFY role ENUM('president','officer','treasurer','finance_officer','auditor') NOT NULL
        ");

        Schema::table('org_memberships', function (Blueprint $table) {

            // restore unique constraint (original behavior)
            $table->unique(
                ['organization_id', 'school_year_id', 'role'],
                'org_memberships_active_unique'
            );

        });
    }
};