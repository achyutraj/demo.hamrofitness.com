<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')
                ->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('added_by')
                ->constrained('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('detail_id')
                ->constrained('common_details')->onUpdate('cascade')->onDelete('cascade');
            $table->string('service_by');
            $table->date('service_date');
            $table->date('next_service_date')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('asset_services');
    }
}
