<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strategic_plan_fund_sources', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')
                ->constrained('strategic_plan_submissions')
                ->cascadeOnDelete();

            
            $table->string('type');

            
            $table->string('label')->nullable();

            $table->decimal('amount', 12, 2)->default(0);

            $table->timestamps();

            $table->index(['submission_id', 'type'], 'spfs_sub_type_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_plan_fund_sources');
    }
};
