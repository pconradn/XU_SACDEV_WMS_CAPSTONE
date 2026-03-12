<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strategic_plan_projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')
                ->constrained('strategic_plan_submissions')
                ->cascadeOnDelete();

            
            $table->string('category');

            $table->date('target_date')->nullable();
            $table->string('title');

            
            $table->string('implementing_body')->nullable();

            $table->decimal('budget', 12, 2)->default(0);

            $table->timestamps();

            $table->index(['submission_id', 'category'], 'spp_sub_cat_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_plan_projects');
    }
};
