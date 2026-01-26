<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseMessageToCustomerSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_sms', function (Blueprint $table) {
            $table->text('response_message')->nullable()->after('status');
            $table->string('sent_from')->nullable()->after('response_message');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_sms', function (Blueprint $table) {
            $table->dropColumn('response_message');
            $table->dropColumn('sent_from');

         });
    }
}
