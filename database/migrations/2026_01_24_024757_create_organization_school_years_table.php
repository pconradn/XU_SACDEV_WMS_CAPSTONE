<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organization_school_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();

            $table->foreignId('president_user_id')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->timestamp('president_confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'school_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_school_years');
    }
};

