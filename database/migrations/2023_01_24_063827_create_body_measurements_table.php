<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_measurements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('client_id')
                ->constrained('gym_clients')->onUpdate('cascade');
            $table->date('entry_date');
            $table->string('added_by')->default('admin');
            $table->string('weight');
            $table->string('fat')->nullable();
            $table->string('height_feet');
            $table->string('height_inches')->nullable();
            $table->string('fore_arms')->nullable();
            $table->string('neck')->nullable();
            $table->string('shoulder')->nullable();
            $table->string('chest')->nullable();
            $table->string('waist')->nullable();
            $table->string('hip')->nullable();
            $table->string('thigh')->nullable();
            $table->string('calves')->nullable();
            $table->string('arms')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_measurements');
    }
}
