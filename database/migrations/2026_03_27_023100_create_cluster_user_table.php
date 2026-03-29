<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cluster_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['cluster_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_user');
    }
};