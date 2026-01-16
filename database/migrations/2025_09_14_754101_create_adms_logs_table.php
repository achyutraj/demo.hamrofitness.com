<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adms_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('detail_id')
            ->constrained('common_details')->onUpdate('cascade')->onDelete('cascade');
            $table->string('device_code')->nullable();
            $table->string('serial_num')->nullable();
            $table->enum('status', ['success', 'failed', 'pending','deployed'])->default('pending');
            $table->json('adms_response');
            $table->text('error_message')->nullable();
            $table->json('filter_response')->nullable();
            $table->integer('fetch_attempt')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['date', 'detail_id']);
            $table->index(['device_code', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adms_logs');
    }
}
