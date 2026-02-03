<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('org_president_terms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years')->restrictOnDelete();

            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

            $table->string('status', 20)->default('active'); // active, ended, impeached, replaced
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('ended_reason')->nullable();
            $table->foreignId('ended_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('created_from_registration_id')
                ->nullable()
                ->constrained('president_registrations')
                ->nullOnDelete();

            $table->timestamps();

            // Allow many terms, but only one active term should exist (enforce in app logic)
            $table->index(['organization_id', 'school_year_id'], 'ix_pres_term_org_sy');
            $table->index(['organization_id', 'school_year_id', 'status'], 'ix_pres_term_org_sy_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_president_terms');
    }
};
