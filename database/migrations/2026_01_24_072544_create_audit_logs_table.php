<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->string('event', 100); // e.g. officers_updated, project_head_assigned
            $table->text('message')->nullable();

            $table->foreignId('actor_user_id')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->foreignId('organization_id')->nullable()
                ->constrained()->nullOnDelete();

            $table->foreignId('school_year_id')->nullable()
                ->constrained()->nullOnDelete();

            // Optional JSON context for later (project_id, officer_id, etc.)
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['event', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
