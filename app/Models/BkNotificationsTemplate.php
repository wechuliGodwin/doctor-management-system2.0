<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkNotificationTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('bk_notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('custom'); // default, urgent, followup, custom
            $table->text('content');
            $table->enum('hospital_branch', ['kijabe', 'westlands', 'naivasha', 'marira'])->default('kijabe');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bk_notification_templates');
    }
}