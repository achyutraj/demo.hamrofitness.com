<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasClientToClassScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
            $table->foreignId('detail_id')->nullable()->after('branch_id')
                ->constrained('common_details')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->after('className')
                ->constrained('classes')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('trainer_id')->nullable()->after('trainer')
                ->constrained()->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('has_client')->default(0)->after('days');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropForeign(['class_id','trainer_id','detail_id']);
            $table->dropColumn(['uuid','has_client','class_id','trainer_id','detail_id']);
        });
    }
}
