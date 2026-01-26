<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOccupationDetailsToBusinessEnquriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_enquiries', function (Blueprint $table) {
            $table->text('occupation_details')->nullable()->default(null)->after('occupation');
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
            $table->dropColumn(['occupation_details']);
        });
    }
}
