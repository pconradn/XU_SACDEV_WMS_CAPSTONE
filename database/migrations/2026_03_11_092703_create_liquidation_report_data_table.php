<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidation_report_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('contact_number')->nullable();

            $table->decimal('finance_amount', 12, 2)->default(0);
            $table->decimal('fund_raising_amount', 12, 2)->default(0);
            $table->decimal('sacdev_amount', 12, 2)->default(0);
            $table->decimal('pta_amount', 12, 2)->default(0);

            $table->enum('fundraising_type', [
                'solicitation',
                'counterpart',
                'ticket_selling',
                'selling'
            ])->nullable();

            $table->decimal('total_funds', 12, 2)->default(0);
            $table->decimal('total_expenses', 12, 2)->default(0);
            $table->decimal('total_advanced', 12, 2)->default(0);

            $table->decimal('balance', 12, 2)->default(0);

            $table->decimal('cluster_a_return', 12, 2)->default(0);
            $table->decimal('cluster_b_return', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidation_report_data');
    }
};