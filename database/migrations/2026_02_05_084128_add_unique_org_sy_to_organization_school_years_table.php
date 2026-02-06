<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_school_years', function (Blueprint $table) {
            $table->unique(['organization_id', 'school_year_id'], 'organization_school_years_org_sy_unique');
        });
    }

    public function down(): void
    {
        Schema::table('organization_school_years', function (Blueprint $table) {
            $table->dropUnique('organization_school_years_org_sy_unique');
        });
    }
};
