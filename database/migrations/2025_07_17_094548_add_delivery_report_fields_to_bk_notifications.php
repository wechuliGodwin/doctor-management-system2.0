<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryReportFieldsToBkNotifications extends Migration
{
    public function up()
    {
        Schema::table('bk_notifications', function (Blueprint $table) {
            $table->string('message_id')->nullable()->after('appointment_id');
            $table->string('delivery_status')->nullable()->after('status_desc');
            $table->string('delivery_desc')->nullable()->after('delivery_status');
            $table->dateTime('delivery_time')->nullable()->after('delivery_desc');
            $table->string('tat')->nullable()->after('delivery_time');
        });
    }

    public function down()
    {
        Schema::table('bk_notifications', function (Blueprint $table) {
            $table->dropColumn(['message_id', 'delivery_status', 'delivery_desc', 'delivery_time', 'tat']);
        });
    }
}
