<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('gym_clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('product_sale_id')
                ->nullable();
            $table->foreign('product_sale_id')
                ->references('id')
                ->on('product_sales')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('branch_id');
            $table->string('payment_id');
            $table->integer('payment_amount');
            $table->enum('payment_source', ['cash', 'credit_card', 'debit_card', 'net_banking','esewa','other','cheque','ime_pay','phone_pay','khalti']);
            $table->dateTime('payment_date');
            $table->string('remarks')
                ->nullable();
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
        Schema::dropIfExists('product_payments');
    }
}
