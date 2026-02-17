<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferredByToGymClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_clients', function (Blueprint $table) {
            $table->boolean('status')->default(1);
            $table->integer('redeem_points')->after('remember_token')->default(0);
            $table->foreignId('referred_client_id')->after('redeem_points')->nullable()->constrained('gym_clients')
            ->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign(['referred_client_id']);
            $table->dropColumn(['status','referred_client_id','redeem_points']);
        });
    }
}
