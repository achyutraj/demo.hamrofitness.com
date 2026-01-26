<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessRenewHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_renew_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_id')->constrained('common_details')
               ->onUpdate('cascade')->onDelete('cascade');
            $table->string('package_offered');
            $table->string('package_amount')->nullable();
            $table->text('remark')->nullable();
            $table->date('renew_start_date');
            $table->date('renew_end_date');
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
        Schema::dropIfExists('business_renew_history');
    }
}
