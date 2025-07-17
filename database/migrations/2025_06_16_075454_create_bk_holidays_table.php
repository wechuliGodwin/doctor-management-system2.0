<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkHolidaysTable extends Migration
{
    public function up()
    {
        Schema::create('bk_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date');
            $table->string('name');
            $table->enum('type', ['public', 'private']);
            $table->string('hospital_branch')->nullable(); // Optional: for branch-specific holidays
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bk_holidays');
    }
}