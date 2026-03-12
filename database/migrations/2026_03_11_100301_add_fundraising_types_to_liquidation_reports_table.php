<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidation_report_data', function (Blueprint $table) {

            $table->dropColumn('fundraising_type');

        });

        Schema::table('liquidation_report_data', function (Blueprint $table) {

            $table->json('fundraising_types')->nullable()->after('fund_raising_amount');

        });
    }

    public function down(): void
    {
        Schema::table('liquidation_report_data', function (Blueprint $table) {

            $table->dropColumn('fundraising_types');

        });

        Schema::table('liquidation_report_data', function (Blueprint $table) {

            $table->enum('fundraising_type', [
                'solicitation',
                'counterpart',
                'ticket_selling',
                'selling'
            ])->nullable();

        });
    }
};