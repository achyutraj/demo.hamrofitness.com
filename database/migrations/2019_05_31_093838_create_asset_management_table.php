<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('tag');
            $table->string('name');
            $table->string('brand_name');
            $table->integer('quantity');
            $table->integer('quantity_working')->nullable()->default('0');
            $table->integer('quantity_repair')->nullable()->default('0');
            $table->integer('quantity_damaged')->nullable()->default('0');
            $table->string('asset_model');
            $table->enum('status',['working','stock','repair','damaged'])->default('stock');
            $table->date('purchase_date');
            $table->string('working_remarks')->nullable();
            $table->string('repair_remarks')->nullable();
            $table->string('damaged_remarks')->nullable();

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
        Schema::dropIfExists('assets');
    }
}
