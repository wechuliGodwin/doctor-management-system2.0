<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBkSpecializationsDayOfWeek extends Migration
{
    public function up()
    {
        Schema::table('bk_specializations', function (Blueprint $table) {
            $table->json('days_of_week')->nullable()->after('limits');
            $table->dropColumn('day_of_week');
        });
    }

    public function down()
    {
        Schema::table('bk_specializations', function (Blueprint $table) {
            $table->enum('day_of_week', [
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'daily'
            ])->nullable()->after('limits');
            $table->dropColumn('days_of_week');
        });
    }
}
