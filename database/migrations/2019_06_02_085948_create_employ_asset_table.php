<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employ_asset', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('employ_id');
            $table->integer('quantity')->nullable();
            $table->integer('working_quantity')->default('0');
            $table->integer('repair_quantity')->default('0');
            $table->integer('damaged_quantity')->default('0');
            $table->string('working_remarks')->nullable();

            $table->foreign('asset_id')
                ->on('assets')
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
        Schema::dropIfExists('employ_asset');
    }
}
