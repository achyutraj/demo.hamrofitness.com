<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceVariationToLockerCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locker_categories', function (Blueprint $table) {
            $table->after('detail_id',function (Blueprint $table){
                $table->string('three_month_price')->nullable();
                $table->string('six_month_price')->nullable();
                $table->string('one_year_price')->nullable();
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
        Schema::table('locker_categories', function (Blueprint $table) {
            $table->dropColumn(['three_month_price','six_month_price','one_year_price']);
        });
    }
}
