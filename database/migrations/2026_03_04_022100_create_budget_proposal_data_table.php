<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_proposal_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->decimal('counterpart_amount_per_pax', 12, 2)->nullable();
            $table->integer('counterpart_pax')->nullable();
            $table->decimal('counterpart_total', 12, 2)->nullable();

            $table->decimal('pta_amount', 12, 2)->nullable();

            $table->decimal('raised_funds', 12, 2)->nullable();

            $table->decimal('amount_charged_to_org', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_proposal_data');
    }
};