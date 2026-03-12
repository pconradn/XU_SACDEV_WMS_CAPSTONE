<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('form_type_required_roles', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('form_type_id');

            $table->enum('role', [
                'president',
                'project_head',
                'treasurer',
                'finance_officer',
                'moderator',
                'sacdev_admin',
                'osa_admin',
            ]);

            $table->timestamps();

            $table->foreign('form_type_id')
                ->references('id')
                ->on('form_types')
                ->cascadeOnDelete();

            $table->unique(['form_type_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_type_required_roles');
    }
};