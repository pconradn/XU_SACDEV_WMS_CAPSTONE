<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitation_sponsorship_report_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('activity_name');

            $table->text('purpose')->nullable();

            $table->date('solicitation_from');
            $table->date('solicitation_to');

            $table->integer('approved_letters_distributed')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitation_sponsorship_report_data');
    }
};