<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('detail_id')->constrained('common_details')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('client_id')->constrained('gym_clients')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->longText('review_text');
            $table->boolean('read_review')->default(0);
            $table->string('status')->default('approved');
            $table->softDeletes();
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
        Schema::dropIfExists('reviews');
    }
}
