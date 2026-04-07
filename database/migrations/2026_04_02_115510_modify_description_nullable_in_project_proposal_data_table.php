<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('org_link')->nullable()->change();
            $table->text('audience_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->text('org_link')->nullable(false)->change();
            $table->text('audience_type')->nullable()->change();
        });
    }
};