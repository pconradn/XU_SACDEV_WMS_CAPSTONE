<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {
            $table->text('area_focus')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {
            $table->string('area_focus', 100)->nullable()->change();
        });
    }
};