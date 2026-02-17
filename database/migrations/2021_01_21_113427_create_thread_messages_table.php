<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->default(null);
            $table->unsignedBigInteger('merchant_id')->nullable()->default(null);
            $table->unsignedBigInteger('detail_id')->nullable()->default(null);
            $table->unsignedBigInteger('employee_id')->nullable()->default(null);

            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('gym_clients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('detail_id')->references('id')->on('common_details')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('message_threads');
    }
}
