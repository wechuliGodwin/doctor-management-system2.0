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
        Schema::create('bk_specializations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->unsignedInteger('group_id');
            $table->integer('limits');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('group_id')->references('id')->on('bk_specialization_group')->onDelete('restrict');

            // Optional: Add foreign key constraint if applicable
            // $table->foreign('group_id')->references('id')->on('specialization_group')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_specializations');
    }
};
