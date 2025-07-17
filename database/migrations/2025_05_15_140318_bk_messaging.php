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
        Schema::create('bk_messaging', function (Blueprint $table) {
            $table->increments('id');
            $table->date('messaging_date');
            $table->unsignedBigInteger('appointment_id');
            $table->text('urgent_message');
            $table->text('sender_name');
            $table->string('sender_department', 64);
            $table->boolean('active')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();


            $table->foreign('appointment_id')->references('id')->on('bk_appointments')->onDelete('cascade');

            // Optional foreign key
            // $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_messaging');
    }
};
