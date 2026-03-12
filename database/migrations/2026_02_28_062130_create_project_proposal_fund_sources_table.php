<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('project_proposal_fund_sources', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('project_proposal_data_id');

            $table->string('source_name'); 
            $table->decimal('amount', 12, 2);

            $table->timestamps();

            $table->foreign('project_proposal_data_id')
                ->references('id')
                ->on('project_proposal_data')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_proposal_fund_sources');
    }
};