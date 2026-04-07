<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'clearance_status')) {
                $table->string('clearance_status')->default('draft')->after('clearance_file_path');
            }

            if (!Schema::hasColumn('projects', 'clearance_issued_at')) {
                $table->timestamp('clearance_issued_at')->nullable()->after('clearance_status');
            }

            if (!Schema::hasColumn('projects', 'clearance_revoked_at')) {
                $table->timestamp('clearance_revoked_at')->nullable()->after('clearance_issued_at');
            }

            if (!Schema::hasColumn('projects', 'clearance_snapshot')) {
                $table->json('clearance_snapshot')->nullable()->after('clearance_revoked_at');
            }

            if (!Schema::hasColumn('projects', 'clearance_token')) {
                $table->text('clearance_token')->nullable()->after('clearance_snapshot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $columns = [
                'clearance_token',
                'clearance_snapshot',
                'clearance_revoked_at',
                'clearance_issued_at',
                'clearance_status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};