<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_sms', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('phone', 16);
            $table->boolean('status');
            //$table->unsignedBigInteger('detail_id');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('sender_id');;
            $table->timestamp('created_at');
            //$table->foreign('detail_id')->references('id')->on('common_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('employes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sender_id')->references('id')->on('merchants')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_sms');
    }
}
