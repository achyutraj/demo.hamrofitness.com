<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymTutorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('gym_tutorial')) {
            Schema::drop('gym_tutorial');
        }
        Schema::create('gym_tutorial', function(Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('title');
            $table->boolean('is_default')->default(0);
            $table->text('description')->nullable();
            $table->text('iframe_code')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('detail_id')->nullable()->constrained('common_details')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->enum('type', ['text', 'video', 'audio', 'image'])->default('text');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_tutorial');
    }
}
