<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employ_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employ_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('leaveType');
            $table->integer('leaveDays');
            $table->string('days');
            $table->string('startDate');
            $table->string('endDate');

            $table->foreign('employ_id')
                ->references('id')
                ->on('employes')
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
        Schema::dropIfExists('employ_leaves');
    }
}
