<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_moderator_terms', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('school_year_id'); // target SY
            $table->unsignedBigInteger('user_id');        // moderator user
            $table->unsignedBigInteger('created_by_user_id')->nullable(); // who nominated/created

            $table->string('status', 30)->default('pending'); // pending, active, ended, replaced

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('ended_reason')->nullable();
            $table->unsignedBigInteger('ended_by_user_id')->nullable();

            $table->timestamp('activated_at')->nullable();

            $table->timestamps();

            // FK (short names to avoid 1059)
            $table->foreign('organization_id', 'omt_org_fk')
                ->references('id')->on('organizations')
                ->cascadeOnDelete();

            $table->foreign('school_year_id', 'omt_sy_fk')
                ->references('id')->on('school_years')
                ->cascadeOnDelete();

            $table->foreign('user_id', 'omt_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('created_by_user_id', 'omt_created_by_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('ended_by_user_id', 'omt_ended_by_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            // one moderator term per org+SY (you can loosen later if you want history inside same SY)
            $table->unique(['organization_id', 'school_year_id'], 'omt_org_sy_unique');

            // helpful indexes
            $table->index(['user_id', 'school_year_id'], 'omt_user_sy_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_moderator_terms');
    }
};
