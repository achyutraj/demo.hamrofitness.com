<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGymMembershipFreezeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_membership_freeze',function(Blueprint $table){
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('client_id')
                ->constrained('gym_clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('purchase_id')
                ->constrained('gym_client_purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('added_by')
                ->constrained('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('detail_id')
                ->constrained('common_details')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('days')->nullable();
            $table->boolean('is_frozen')->default(true);
            $table->date('start_date');
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('gym_membership_freeze');
    }
}
