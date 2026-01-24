<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('officer_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();

            $table->string('full_name');
            $table->string('email');
            $table->string('position')->nullable(); // optional for Sprint 1

            $table->timestamps();

            // Prevent duplicate same email within same org+SY
            $table->unique(['organization_id', 'school_year_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officer_entries');
    }
};
