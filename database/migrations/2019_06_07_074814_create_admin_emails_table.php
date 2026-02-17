<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_emails', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->boolean('status');
            $table->string('subject', 64);
            $table->unsignedBigInteger('detail_id');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('sender_id');;
            $table->timestamp('created_at');
            $table->foreign('detail_id')->references('id')->on('common_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('admin_emails');
    }
}
