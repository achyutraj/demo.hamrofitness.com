<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_points', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('redeem_points')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status')->default(false);
            $table->boolean('is_redeem_reduce')->default(true);
            $table->foreignId('detail_id')->constrained('common_details')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('membership_id')->constrained('gym_memberships')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redeem_points');
    }
}
