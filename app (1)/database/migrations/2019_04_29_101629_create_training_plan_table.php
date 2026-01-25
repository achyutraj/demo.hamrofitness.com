<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->integer('branch_id');
            $table->string('days');
            $table->string('level')->nullable();
            $table->string('activity')->nullable();
            $table->string('sets')->nullable();
            $table->string('repetition')->nullable();
            $table->string('weights')->nullable();
            $table->string('restTime')->nullable();
            $table->string('startDate')->nullable();
            $table->string('endDate')->nullable();

            $table->foreign('client_id')
                ->on('gym_clients')
                ->references('id')
                ->onDelete('cascade');
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
        Schema::dropIfExists('training_plans');
    }
}
