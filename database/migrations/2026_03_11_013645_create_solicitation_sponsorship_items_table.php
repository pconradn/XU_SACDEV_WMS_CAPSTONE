<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitation_sponsorship_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('solicitation_sponsorship_report_id');

            $table->foreign(
                'solicitation_sponsorship_report_id',
                'fk_sol_sponsorship_report'
            )
            ->references('id')
            ->on('solicitation_sponsorship_report_data')
            ->cascadeOnDelete();

            $table->string('control_number');
            $table->string('person_in_charge');
            $table->string('recipient');
            $table->decimal('amount_given', 12, 2)->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitation_sponsorship_items');
    }
};