<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToGymEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_enquiries', function (Blueprint $table) {
            $table->string('customer_mname')->after('customer_name')->nullable();
            $table->string('customer_lname')->after('customer_mname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_enquiries', function (Blueprint $table) {
            $table->dropColumn(['customer_mname','customer_lname']);
        });
    }
}
