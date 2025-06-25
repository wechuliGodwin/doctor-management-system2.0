<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('iserc_research', function (Blueprint $table) {
            $table->id();
            $table->string('pi_name');
            $table->string('pi_email');
            $table->string('pi_address');
            $table->bigInteger('pi_mobile');
            $table->string('human_subjects_training');
            $table->string('pi_cv_path');
            $table->string('co_investigator_1')->nullable();
            $table->string('co_investigator_2')->nullable();
            $table->string('co_investigator_3')->nullable();
            $table->string('co_investigator_cv_paths');
            $table->string('research_proposal_path');
            $table->string('additional_documents_paths')->nullable();
            $table->string('conflicts');
            $table->text('conflictExplanation')->nullable();
            $table->string('dataSharing');
            $table->string('dataSharingAgreement')->nullable();
            $table->string('identifiableData');
            $table->string('dataTransferAgreement')->nullable();
            $table->string('supervisors');
            $table->string('localSupervisor');
            $table->string('department');
            $table->bigInteger('supervisorPhone');
            $table->string('supervisorEmail');
            $table->string('ethicalReview');
            $table->text('reviewDetails');
            $table->string('payment_reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iserc_research');
    }
};

