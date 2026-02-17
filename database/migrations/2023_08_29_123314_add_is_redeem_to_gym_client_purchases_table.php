<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRedeemToGymClientPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_client_purchases', function (Blueprint $table) {
            $table->boolean('is_redeem')->after('is_renew')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_client_purchases', function (Blueprint $table) {
            $table->dropColumn('is_redeem');
        });
    }
}
