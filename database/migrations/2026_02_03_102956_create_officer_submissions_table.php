<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officer_submissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('target_school_year_id');
            $table->unsignedBigInteger('encoded_by_user_id')->nullable();

            $table->string('status')->default('draft');
            $table->boolean('certified')->default(false);

            $table->unsignedBigInteger('sacdev_reviewed_by_user_id')->nullable();
            $table->text('sacdev_remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('sacdev_reviewed_at')->nullable();

            // future-proof request-to-edit
            $table->boolean('edit_requested')->default(false);
            $table->text('edit_request_reason')->nullable();
            $table->unsignedBigInteger('edit_requested_by_user_id')->nullable();
            $table->timestamp('edit_requested_at')->nullable();

            $table->timestamps();

            $table->foreign('organization_id', 'os_org_fk')
                ->references('id')->on('organizations')
                ->cascadeOnDelete();

            $table->foreign('target_school_year_id', 'os_sy_fk')
                ->references('id')->on('school_years')
                ->cascadeOnDelete();

            $table->foreign('encoded_by_user_id', 'os_encoded_by_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('sacdev_reviewed_by_user_id', 'os_sacdev_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('edit_requested_by_user_id', 'os_edit_req_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->unique(['organization_id', 'target_school_year_id'], 'os_org_sy_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officer_submissions');
    }
};
