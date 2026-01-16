<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckOutToGymClientAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_client_attendances', function (Blueprint $table) {
            $table->dateTime('check_out')->nullable()->after('check_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_client_attendances', function (Blueprint $table) {
            $table->dropColumn(['check_out']);
        });
    }
}
