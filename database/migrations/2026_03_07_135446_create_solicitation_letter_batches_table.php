<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitation_letter_batches', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('approved_letter_count');

            $table->string('control_series_start');
            $table->string('control_series_end');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitation_letter_batches');
    }
};
