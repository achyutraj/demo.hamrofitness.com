<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGymClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->date('dob');
            $table->enum('gender', ['male', 'female']);
            $table->string('email', 50);
            $table->longText('address')
                ->nullable();
            $table->string('mobile', 15);
            $table->integer('age');
            $table->date('joining_date')
                ->nullable();
            $table->double('weight', 8, 2);
            $table->double('fat')->nullable()->default(null);
            $table->tinyInteger('height_feet')->nullable()->default(null);
            $table->tinyInteger('height_inches')->nullable()->default(null);
            $table->tinyInteger('chest')->nullable()->default(null);
            $table->tinyInteger('waist')->nullable()->default(null);
            $table->tinyInteger('arms')->nullable()->default(null);
            $table->string('image', 255)
                ->nullable();
            $table->enum('client_source', ['huntplex', 'direct']);
            $table->enum('marital_status', ['yes', 'no'])
                ->default('no');
            $table->date('anniversary')
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
        Schema::drop('gym_clients');
    }
}
