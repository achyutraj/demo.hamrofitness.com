<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_id')
                ->constrained('common_details')->onUpdate('cascade')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->string('ip_address');
            $table->string('serial_num');
            $table->boolean('device_status')->default(true);
            $table->string('port_num')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_model')->nullable();
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
        Schema::dropIfExists('devices');
    }
}
