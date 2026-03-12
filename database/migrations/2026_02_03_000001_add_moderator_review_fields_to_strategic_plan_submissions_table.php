<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('strategic_plan_submissions', function (Blueprint $table) {
           



            
            $table->index('status', 'sp_status_idx');
            $table->index(['organization_id', 'target_school_year_id'], 'sp_org_targetsy_idx');
            $table->index('submitted_to_moderator_at', 'sp_submitted_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('strategic_plan_submissions', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('sp_status_idx');
            $table->dropIndex('sp_org_targetsy_idx');
            $table->dropIndex('sp_submitted_at_idx');


        });
    }
};
