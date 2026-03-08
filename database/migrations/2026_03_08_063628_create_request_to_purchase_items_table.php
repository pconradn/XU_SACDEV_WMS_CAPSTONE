<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_to_purchase_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_to_purchase_id')
                ->references('id')
                ->on('request_to_purchase_data')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable();

            $table->text('particulars');

            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);

            $table->string('vendor')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_to_purchase_items');
    }
};