<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees_collection_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('fees_collection_report_id')
                ->references('id')
                ->on('fees_collection_report_data')
                ->cascadeOnDelete();

            $table->integer('number_of_payers');

            $table->decimal('amount_paid', 12, 2);

            $table->string('receipt_series')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees_collection_items');
    }
};