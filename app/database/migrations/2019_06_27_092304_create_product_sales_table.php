<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->string('customer_type');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('employ_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('product_name');
            $table->string('product_price');
            $table->string('product_quantity');
            $table->string('product_discount');
            $table->string('product_amount');
            $table->integer('branch_id');

            $table->foreign('client_id')
                ->on('gym_clients')
                ->references('id')
                ->onDelete('cascade');
            $table->foreign('employ_id')
                ->on('employes')
                ->references('id')
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
        Schema::dropIfExists('product_sales');
    }
}
