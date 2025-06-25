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
        Schema::create('bk_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('appointment_id');
            $table->date('notification_date');
            $table->time('appointment_time');
            $table->string('status', 10);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key constraint
            $table->foreign('appointment_id')
                ->references('id')->on('bk_appointments')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_notifications');

    }
};
