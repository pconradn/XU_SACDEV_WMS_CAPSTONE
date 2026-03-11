<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentation_report_data', function (Blueprint $table) {
            if (Schema::hasColumn('documentation_report_data', 'implementation_date')) {
                $table->dropColumn('implementation_date');
            }

            $table->date('implementation_start_date')
                ->nullable()
                ->after('description');

            $table->date('implementation_end_date')
                ->nullable()
                ->after('implementation_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('documentation_report_data', function (Blueprint $table) {
            $table->dropColumn([
                'implementation_start_date',
                'implementation_end_date',
            ]);

            $table->date('implementation_date')
                ->nullable()
                ->after('description');
        });
    }
};