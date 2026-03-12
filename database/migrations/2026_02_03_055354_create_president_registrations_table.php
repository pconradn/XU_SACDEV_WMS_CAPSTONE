



<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('president_registration_leaderships', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('president_registration_id');
            $table->foreign('president_registration_id', 'fk_preslead_reg')
                ->references('id')
                ->on('president_registrations')
                ->cascadeOnDelete();

            $table->string('organization_name')->nullable();
            $table->string('position')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('inclusive_years', 30)->nullable();

            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            $table->index('president_registration_id', 'ix_preslead_reg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('president_registration_leaderships');
    }
};
