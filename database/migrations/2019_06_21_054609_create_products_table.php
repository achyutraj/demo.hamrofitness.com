<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('tag');
            $table->string('name');
            $table->string('brand_name');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('quantity_sold')->nullable()->default('0');
            $table->integer('quantity_expired')->nullable()->default('0');
            $table->enum('status',['stock','expired'])->default('stock');
            $table->date('purchase_date');
            $table->date('expire_date')->nullable();
            $table->foreign('branch_id')
                ->references('id')
                ->on('common_details')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('products');
    }
}
