<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {
            $table->string('sdg', 3000)->change();
            $table->string('project_nature', 3000)->change();
        });

    }

    public function down(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {
            $table->string('sdg', 255)->change();
        });
    }
};