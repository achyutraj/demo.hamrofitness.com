<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOldToGymSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_settings', function (Blueprint $table) {
            $table->after('sms_password',function(Blueprint $table) {
                $table->boolean('is_old')->default(true); // for new sms url ,is_old is false
                $table->string('campaign_id')->nullable();
                $table->string('route_id')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_settings', function (Blueprint $table) {
            //
        });
    }
}
