<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddSmsCredentialsToGymSettingsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('gym_settings', function (Blueprint $table) {
                $table->enum('sms_status', array('disabled', 'enabled'))->after('aws_bucket');
                $table->enum('sms_api_url', array('https://api.ininepal.com/api/index'))->after('sms_status');
                $table->string('sms_sender_id')->after('sms_api_url')->nullable()->default(null);
                $table->string('sms_username')->after('sms_sender_id')->nullable()->default(null);
                $table->string('sms_password')->after('sms_username')->nullable()->default(null);
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
                $table->dropColumn(array('sms_status', 'sms_api_url', 'sms_sender_id', 'sms_username', 'sms_password'));
            });
        }
    }
