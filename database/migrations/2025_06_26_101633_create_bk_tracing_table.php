<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkTracingTable extends Migration
{
    public function up()
    {
        Schema::create('bk_tracing', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('appointment_id')->unsigned();
            $table->date('tracing_date');
            $table->enum('status', ['contacted', 'not_contacted', 'other'])->default('not_contacted');
            $table->text('message')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('appointment_id')->references('id')->on('bk_appointments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bk_tracing');
    }
}