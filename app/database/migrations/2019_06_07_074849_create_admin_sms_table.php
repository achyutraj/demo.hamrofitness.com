<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_sms', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('phone', 16);
            $table->boolean('status');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('sender_id');;
            $table->timestamp('created_at');
            $table->foreign('recipient_id')->references('id')->on('gym_clients')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('admin_sms');
    }
}
