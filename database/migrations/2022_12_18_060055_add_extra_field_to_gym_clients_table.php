<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldToGymClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_clients', function (Blueprint $table) {
            $table->string('emergency_contact')->after('mobile')->nullable();
            $table->string('blood_group')->after('emergency_contact')->nullable();
            $table->boolean('is_gym_experience')->after('blood_group')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_clients', function (Blueprint $table) {
            $table->dropColumn(['blood_group','emergency_contact','is_gym_experience']);
        });
    }
}
