<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('org_constitution_submissions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();

            $table->string('file_path');
            $table->string('original_filename');

            $table->foreignId('submitted_by_user_id')->nullable()->constrained('users');

            $table->timestamp('submitted_at')->nullable();

            $table->string('status')->default('draft');
            // draft
            // submitted
            // approved_by_sacdev
            // returned

            $table->text('remarks')->nullable();

            $table->foreignId('approved_by_user_id')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique([
                'organization_id',
                'school_year_id'
            ], 'org_constitution_unique_per_sy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_constitution_submissions');
    }
};
