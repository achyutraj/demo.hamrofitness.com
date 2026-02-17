<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymMembershipExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_membership_extends', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('client_id')
                ->constrained('gym_clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('purchase_id')
                ->constrained('gym_client_purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('extend_by')
                ->constrained('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('detail_id')
                ->constrained('common_details')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('days');
            $table->date('extend_from');
            $table->date('extend_to');
            $table->text('reasons');
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
        Schema::dropIfExists('gym_membership_extends');
    }
}
