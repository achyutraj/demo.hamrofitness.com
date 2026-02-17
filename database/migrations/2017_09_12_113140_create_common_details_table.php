<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_details', function (Blueprint $table) {
             $table->id();
            $table->string('title', 200);
            $table->text('address');
            $table->decimal('longitude', 10, 8)
                ->nullable();
            $table->decimal('latitude', 10, 8)
                ->nullable();
            $table->string('slug', 250);
            $table->string('owner_incharge_name', 150);
            $table->text('phone');
            $table->string('owner_incharge_name2', 150)
                ->nullable();
            $table->text('phone2')
                ->nullable();
            $table->string('email', 150);
            $table->string('bitly_link', 100)
                ->nullable();
            $table->string('search_title', 200)->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])
                ->default('active');
            $table->dateTime('last_updated')
                ->nullable();
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
        Schema::drop('common_details');
    }
}
