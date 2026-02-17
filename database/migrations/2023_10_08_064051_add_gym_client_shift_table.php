<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGymClientShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('device_shift');

        Schema::create('gym_client_shift', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('gym_clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('shift_id')
                ->constrained()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('gym_client_shift');
    }
}
