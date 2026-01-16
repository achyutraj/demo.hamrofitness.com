<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymClientClassScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_client_class_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('gym_clients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained('class_schedules')
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
        Schema::dropIfExists('gym_client_class_schedule');
    }
}
