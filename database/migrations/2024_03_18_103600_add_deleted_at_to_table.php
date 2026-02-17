<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['assets','asset_services','employes','employ_leaves','gym_client_attendances','gym_client_class_schedule',
        'gym_invoice','gym_invoice_items','gym_membership_extends','gym_membership_freeze','lockers','payroll','products','product_sales'
        ,'training_plans','employ_asset','class_schedules','gym_suppliers','trainers'];
        foreach($tables as $key => $value){
            Schema::table($value, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
