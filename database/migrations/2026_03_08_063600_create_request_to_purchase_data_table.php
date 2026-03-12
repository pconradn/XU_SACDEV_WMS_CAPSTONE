<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_to_purchase_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('xu_finance_amount', 12, 2)->default(0);
            $table->decimal('membership_fee_amount', 12, 2)->default(0);
            $table->decimal('pta_amount', 12, 2)->default(0);
            $table->decimal('solicitations_amount', 12, 2)->default(0);

            $table->decimal('others_amount', 12, 2)->default(0);
            $table->string('others_label')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_to_purchase_data');
    }
};