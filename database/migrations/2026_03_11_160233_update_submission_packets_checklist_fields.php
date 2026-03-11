<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('submission_packets', function (Blueprint $table) {

            $table->renameColumn('has_liquidation_report', 'has_solicitation_letter');

            $table->text('other_items')->nullable();



        });
    }

    public function down(): void
    {
        Schema::table('submission_packets', function (Blueprint $table) {

            $table->renameColumn('has_solicitation_letter', 'has_liquidation_report');

            $table->dropColumn('other_items');


        });
    }
};