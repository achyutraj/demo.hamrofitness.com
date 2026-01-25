<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEsewaMerchantIdToGymSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_settings', function (Blueprint $table) {
            $table->enum('payment_status', array('disabled', 'enabled'))->after('mail_email');
            $table->string('esewa_merchant_id')->nullable()->after('payment_status');
            $table->string('khalti_public_key')->nullable()->after('esewa_merchant_id');
            $table->string('khalti_secret_key')->nullable()->after('khalti_public_key');
            $table->longText('offline_text')->nullable()->after('khalti_secret_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_settings', function (Blueprint $table) {
            $table->dropColumn(['payment_status','esewa_merchant_id','khalti_public_key','khalti_secret_key']);
        });
    }
}
