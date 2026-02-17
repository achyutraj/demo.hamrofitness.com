<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employ_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('heading', 100);
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            $table->date('reminder_date')->nullable();
            $table->enum('status', ['pending', 'complete'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
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
        Schema::dropIfExists('employ_tasks');
    }
}
