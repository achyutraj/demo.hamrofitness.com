<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeDOBEmailAgeColumnNullOnGymEnquiries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `gym_enquiries` CHANGE `dob` `dob` DATE NULL;');
        DB::statement('ALTER TABLE `gym_enquiries` CHANGE `email` `email` VARCHAR(50) NULL;');
        DB::statement('ALTER TABLE `gym_enquiries` CHANGE `age` `age` TINYINT(4) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
