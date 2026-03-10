<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selling_activity_report_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('selling_activity_report_id')
                ->references('id')
                ->on('selling_activity_report_data')
                ->cascadeOnDelete();

            $table->integer('quantity');

            $table->string('particulars');
            $table->decimal('price', 12, 2);
            $table->decimal('amount', 12, 2)->nullable();

            $table->string('acknowledgement_receipt_number')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selling_activity_report_items');
    }
};