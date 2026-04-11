<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profile_leaderships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_profile_id')->constrained()->cascadeOnDelete();

            $table->string('organization_name');
            $table->string('position')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('inclusive_years')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile_leaderships');
    }
};