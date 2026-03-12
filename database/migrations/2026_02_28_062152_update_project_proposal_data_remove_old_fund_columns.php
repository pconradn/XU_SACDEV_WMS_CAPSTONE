<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {

            $table->dropColumn([
                'source_of_funds',
                'counterpart_amount',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {

            $table->string('source_of_funds')->nullable();
            $table->decimal('counterpart_amount', 12, 2)->nullable();

        });
    }
};