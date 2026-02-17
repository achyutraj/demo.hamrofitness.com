<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->longText('comment');
            $table->boolean('read_review')->default(0);
            $table->foreignId('merchant_id')->nullable()->constrained('merchants')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('client_id')->nullable()->constrained('gym_clients')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('review_comments');
    }
}
