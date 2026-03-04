<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('budget_proposal_data_id')
                ->references('id')
                ->on('budget_proposal_data')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('section');

            $table->integer('qty')->nullable();

            $table->string('unit')->nullable();

            $table->text('particulars');

            $table->decimal('price_per_unit', 12, 2)->nullable();

            $table->decimal('amount', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};