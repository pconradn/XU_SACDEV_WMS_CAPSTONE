<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_selling_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ticket_selling_report_id');

            $table->foreign(
                'ticket_selling_report_id',
                'fk_ticket_selling_report'
            )
            ->references('id')
            ->on('ticket_selling_report_data')
            ->cascadeOnDelete();

            $table->integer('quantity');

            $table->string('series_control_numbers');

            $table->decimal('price_per_ticket', 12, 2);

            $table->decimal('amount', 12, 2);

            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_selling_items');
    }
};