<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceIdToSyncLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->foreignId('device_id')->nullable()
            ->constrained()->after('client_id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->dropForeign(['device_id']);
            $table->dropColumn('device_id');

        });
    }
}
