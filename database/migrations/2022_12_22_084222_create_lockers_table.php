<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lockers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('detail_id')->constrained('common_details')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('locker_category_id')->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('locker_num');
            $table->enum('status',['available','reserved','switch','maintenance','destroy','requested','repaired']);
            $table->text('details')->nullable();
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
        Schema::dropIfExists('lockers');
    }
}
