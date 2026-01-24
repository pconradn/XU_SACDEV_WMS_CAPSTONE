<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('assignment_role')->default('member'); // head/member
            $table->timestamp('archived_at')->nullable();

            $table->timestamps();

            $table->unique(['project_id', 'user_id', 'assignment_role']);
            $table->index(['user_id', 'assignment_role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_assignments');
    }
};
