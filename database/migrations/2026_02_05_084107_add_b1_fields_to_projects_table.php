<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // From StrategicPlanProject (B-1)
            $table->string('category', 64)->nullable()->after('title');
            $table->date('target_date')->nullable()->after('category');
            $table->string('implementing_body', 255)->nullable()->after('target_date');
            $table->decimal('budget', 12, 2)->nullable()->after('implementing_body');

            // Traceability back to B-1 project row (optional but recommended)
            $table->unsignedBigInteger('source_strategic_plan_project_id')->nullable()->after('budget');
            $table->index('source_strategic_plan_project_id');

            // Optional: prevent duplicate “same title” for same org+sy
            // Comment out if your org can have identical titles.
            // $table->unique(['organization_id', 'school_year_id', 'title'], 'projects_org_sy_title_unique');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // If you enabled the unique above, also drop it here:
            // $table->dropUnique('projects_org_sy_title_unique');

            $table->dropIndex(['source_strategic_plan_project_id']);
            $table->dropColumn([
                'category',
                'target_date',
                'implementing_body',
                'budget',
                'source_strategic_plan_project_id',
            ]);
        });
    }
};
