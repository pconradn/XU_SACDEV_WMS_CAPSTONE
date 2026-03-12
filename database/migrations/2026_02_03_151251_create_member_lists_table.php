<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_lists', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('target_school_year_id');
            $table->unsignedBigInteger('encoded_by_user_id')->nullable();

            // optional (not required since there's no submit)
            $table->boolean('certified')->default(false);

            $table->timestamps();

            $table->foreign('organization_id', 'ml_org_fk')
                ->references('id')->on('organizations')
                ->cascadeOnDelete();

            $table->foreign('target_school_year_id', 'ml_sy_fk')
                ->references('id')->on('school_years')
                ->cascadeOnDelete();

            $table->foreign('encoded_by_user_id', 'ml_encoded_by_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            // one list per org+sy
            $table->unique(['organization_id', 'target_school_year_id'], 'ml_org_sy_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_lists');
    }
};
