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
        Schema::create('selling_application_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('selling_application_data_id')
                ->references('id')
                ->on('selling_application_data')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity');

            $table->string('particulars');

            $table->decimal('selling_price', 10, 2);

            $table->string('remarks')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_application_items');
    }
};
