<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // Sprint 2 ready
            $table->string('status')->default('active'); // active/completed/etc.
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['organization_id', 'school_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
