<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToExpenseManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_management', function (Blueprint $table) {
            $table->enum('payment_source', ['cash', 'credit_card', 'debit_card', 'net_banking','esewa','other','cheque','ime_pay','phone_pay','khalti'])
                    ->nullable()->after('price');
            $table->enum('payment_status',['paid','unpaid'])->default('paid')->after('payment_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_management', function (Blueprint $table) {
            $table->dropColumn('payment_source');
            $table->dropColumn('payment_status');

        });
    }
}
