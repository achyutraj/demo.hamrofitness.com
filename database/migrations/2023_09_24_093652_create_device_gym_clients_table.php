<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceGymClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_gym_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')
                ->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')
                ->constrained('gym_clients')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('device_gym_clients');
    }
}
