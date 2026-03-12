<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidation_report_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('section_label')->nullable();

            $table->date('date')->nullable();

            $table->string('particulars');

            $table->decimal('amount', 12, 2)->default(0);

            $table->enum('source_document_type', [
                'OR',
                'SR',
                'CI',
                'SI',
                'AR',
                'PV'
            ])->nullable();

            $table->string('source_document_description')->nullable();

            $table->string('or_number')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidation_report_items');
    }
};