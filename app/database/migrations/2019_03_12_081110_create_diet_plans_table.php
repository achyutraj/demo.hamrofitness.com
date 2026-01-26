<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDietPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->integer('branch_id');
            $table->string('days');
            $table->string('breakfast');
            $table->string('lunch');
            $table->string('dinner');
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
        Schema::dropIfExists('diet_plans');
    }
}
