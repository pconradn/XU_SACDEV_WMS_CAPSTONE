<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('president_registration_awards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('president_registration_id');
            $table->foreign('president_registration_id', 'fk_presaward_reg')
                ->references('id')
                ->on('president_registrations')
                ->cascadeOnDelete();

            $table->string('award_name')->nullable();
            $table->text('award_description')->nullable();
            $table->string('conferred_by')->nullable();
            $table->date('date_received')->nullable();

            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            $table->index('president_registration_id', 'ix_presaward_reg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('president_registration_awards');
    }
};
