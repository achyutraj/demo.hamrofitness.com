<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockerCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('title');
            $table->string('price');
            $table->integer('duration');
            $table->string('duration_type');
            $table->text('details')->nullable();
            $table->foreignId('detail_id')
                ->constrained('common_details')->onUpdate('cascade');
            $table->foreignId('category_id')->nullable()->constrained()
                ->onUpdate('cascade');
            $table->foreignId('area_id')->nullable()->constrained()
                ->onUpdate('cascade')->nullOnDelete();
            $table->enum('type',['small','medium','large'])->default('small');

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
        Schema::dropIfExists('locker_categories');
    }
}
