<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployIdToEmployTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employ_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('employ_id')->after('id')->nullable();
            $table->unsignedBigInteger('detail_id')->after('employ_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employ_tasks', function (Blueprint $table) {
            $table->dropColumn(['employ_id','detail_id']);
        });
    }
}
