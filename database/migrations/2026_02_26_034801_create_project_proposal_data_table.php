<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('project_proposal_data', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('project_document_id')->unique();

            $table->date('start_date');
            $table->date('end_date');

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('venue_type', ['on_campus', 'off_campus']);
            $table->string('venue_name');

            $table->enum('engagement_type', [
                'organizer',
                'partner',
                'participant',
            ]);

            $table->string('main_organizer')->nullable();

            $table->string('project_nature');
            $table->string('project_nature_other')->nullable();

            $table->string('sdg');

            $table->enum('area_focus', [
                'organizational_development',
                'student_services',
                'community_involvement',
            ]);

            $table->text('description');
            $table->text('org_link');

            $table->string('org_cluster')->nullable();

            $table->decimal('total_budget', 12, 2)->nullable();

            $table->string('source_of_funds');
            $table->decimal('counterpart_amount', 12, 2)->nullable();

            $table->string('audience_type');
            $table->string('audience_details')->nullable();

            $table->integer('expected_xu_participants')->nullable();
            $table->integer('expected_non_xu_participants')->nullable();

            $table->boolean('has_guest_speakers')->default(false);

            $table->text('sacdev_remarks')->nullable();

            $table->timestamps();

            $table->foreign('project_document_id')
                ->references('id')
                ->on('project_documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_proposal_data');
    }
};