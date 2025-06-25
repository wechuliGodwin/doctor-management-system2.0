<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bk_appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('appointment_number', 20)->unique();
            $table->text('full_name');
            $table->string('patient_number', 50);
            $table->string('email', 100)->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time')->nullable();
            $table->integer('specialization');
            $table->string('doctor_name', 100)->nullable();

            $table->enum('booking_type', ['new', 'post_op', 'review']);
            $table->string('phone', 20);

            $table->enum('hospital_branch', ['kijabe', 'naivasha', 'westlands', 'marira'])->default('kijabe');
            $table->enum('appointment_status', ['pending', 'early', 'honoured', 'missed', 'late', 'archived'])->default('pending');

            $table->text('doctor_comments')->nullable();
            $table->boolean('rescheduled')->default(false);
            $table->boolean('reminder_cleared')->default(false);
            $table->boolean('visit_status')->default(false);
            $table->date('visit_date')->nullable();

            // HMIS fields
            $table->date('hmis_visit_date')->nullable();
            $table->string('hmis_department', 128)->nullable();
            $table->string('hmis_appointment_purpose', 255)->nullable();
            $table->string('hmis_doctor', 128)->nullable();
            $table->string('hmis_county', 128)->nullable();
            $table->enum('hmis_visit_status', ['pending', 'early', 'honoured', 'missed', 'late', 'archived'])->default('pending');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('specialization')->references('id')->on('bk_specializations')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bk_appointments');
    }
};
