<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strategic_plan_beneficiaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('strategic_plan_projects')
                ->cascadeOnDelete();

            $table->text('text');

            $table->timestamps();

            $table->index('project_id', 'spb_proj_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_plan_beneficiaries');
    }
};
