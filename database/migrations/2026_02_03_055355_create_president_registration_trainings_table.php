<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('president_registration_trainings', function (Blueprint $table) {
            $table->id();

            // FK (manual name to avoid "identifier too long")
            $table->unsignedBigInteger('president_registration_id');
            $table->foreign('president_registration_id', 'fk_prestrain_reg')
                ->references('id')
                ->on('president_registrations')
                ->cascadeOnDelete();

            $table->string('seminar_title')->nullable();
            $table->string('organizer')->nullable();
            $table->string('venue')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();

            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            $table->index('president_registration_id', 'ix_prestrain_reg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('president_registration_trainings');
    }
};
