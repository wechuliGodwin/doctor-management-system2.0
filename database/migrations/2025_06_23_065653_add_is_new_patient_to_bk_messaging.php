<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsNewPatientToBkMessaging extends Migration
{
    public function up()
    {
        Schema::table('bk_messaging', function (Blueprint $table) {
            $table->boolean('is_new_patient')->default(0)->after('appointment_id');
        });
    }

    public function down()
    {
        Schema::table('bk_messaging', function (Blueprint $table) {
            $table->dropColumn('is_new_patient');
        });
    }
}