<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('mobile', 15);
            $table->string('email', 150);
            $table->string('position', 150);
            $table->date('date_of_birth')
                ->nullable();
            $table->string('username', 150);
            $table->string('role');
            $table->string('password', 150);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('detail_id')->nullable();
            $table->foreign('detail_id')
                ->references('id')
                ->on('common_details')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('branch_id')
                ->references('id')
                ->on('business_branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('image', 255)
                ->nullable();
            $table->enum('gender', ['male', 'female'])
                ->default('male');
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
        Schema::dropIfExists('employes');
    }
}
