<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_assignments', function (Blueprint $table) {
            $table->string('role', 50)->nullable()->after('project_id');

            
            $table->unique(['project_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::table('project_assignments', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'role']);
            $table->dropColumn('role');
        });
    }
};

