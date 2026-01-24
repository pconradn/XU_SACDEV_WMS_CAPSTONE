<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('org_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('role'); // 'president','treasurer','moderator','member'
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'school_year_id', 'user_id', 'role'],'orgm_org_sy_user_role_uq');

                $table->index(['organization_id', 'school_year_id', 'role'],'orgm_org_sy_role_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_memberships');
    }
};
