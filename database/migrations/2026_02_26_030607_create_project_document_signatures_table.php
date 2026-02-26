<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_document_signatures', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_document_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->enum('role', [
                'president',
                'project_head',
                'treasurer',
                'finance_officer',
                'moderator',
                'sacdev_admin',
                'osa_admin',
            ])->index();

            $table->enum('status', [
                'pending',
                'signed',
                'returned',
            ])->default('pending')->index();

            $table->timestamp('signed_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('project_document_id')->references('id')->on('project_documents')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->unique(['project_document_id', 'role'], 'uniq_document_role_signature');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_document_signatures');
    }
};