<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToDietPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->string('meal_4')->after('dinner')->nullable();
            $table->string('meal_5')->after('meal_4')->nullable();
            $table->string('image')->after('meal_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->dropColumn(['image','meal_4','meal_5']);
        });
    }
}
