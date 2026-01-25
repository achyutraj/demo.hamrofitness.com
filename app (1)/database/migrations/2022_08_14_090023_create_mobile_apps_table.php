<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_id');
            $table->foreign('detail_id')->references('id')->on('common_details')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->longText('about')->nullable();
            $table->longText('services')->nullable();
            $table->longText('price_plan')->nullable();
            $table->string('logo')->nullable();
            $table->string('offer_image')->nullable();
            $table->string('banner_image1')->nullable();
            $table->string('banner_image2')->nullable();
            $table->string('banner_image3')->nullable();
            $table->string('address')->nullable();
            $table->string('fb_url')->nullable();
            $table->string('google_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('contact_mail')->nullable();
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
        Schema::dropIfExists('mobile_apps');
    }
}
