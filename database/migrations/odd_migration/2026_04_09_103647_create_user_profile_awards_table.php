<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profile_awards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_profile_id')->constrained()->cascadeOnDelete();

            $table->string('award_name');
            $table->text('award_description')->nullable();
            $table->string('conferred_by')->nullable();

            $table->date('date_received')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile_awards');
    }
};