<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockerReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('detail_id')->constrained('common_details')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('locker_id')->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('gym_clients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('assign_by')->constrained('merchants')->nullable()
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('purchase_amount');
            $table->integer('discount')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->integer('amount_to_be_paid');
            $table->date('purchase_date');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->string('payment_required')->default('yes');
            $table->enum('status',['active','pending'])->default('active');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('locker_reservations');
    }
}
