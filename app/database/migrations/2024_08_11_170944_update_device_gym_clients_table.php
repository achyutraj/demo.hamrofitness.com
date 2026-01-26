<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDeviceGymClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_gym_clients', function (Blueprint $table) {
            $table->boolean('is_device_deleted')->default(false);
            $table->boolean('is_denied')->default(false);
            $table->boolean('is_expired')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_gym_clients', function (Blueprint $table) {
            $table->dropColumn(['is_denied', 'is_expired', 'is_device_deleted']);
        });
    }
}
