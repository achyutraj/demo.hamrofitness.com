<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('detail_id')->constrained('common_details')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('reservation_id')->constrained('locker_reservations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('gym_clients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('payment_id');
            $table->integer('payment_amount');
            $table->enum('payment_source', ['cash', 'credit_card', 'debit_card', 'net_banking','esewa','other','cheque','ime_pay','phone_pay','khalti'])
                ->default('cash');
            $table->date('payment_date');
            $table->text('remarks')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('locker_payments');
    }
}
