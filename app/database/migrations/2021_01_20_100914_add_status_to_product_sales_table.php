<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToProductSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_sales', function (Blueprint $table) {
            $table->integer('total_amount')->after('product_amount');
            $table->integer('paid_amount')->after('total_amount');
            $table->enum('payment_required', ['yes', 'no'])
                ->default('yes');
            $table->date('next_payment_date')->after('payment_required')
                ->nullable();
            $table->enum('status', ['active', 'pending'])
                ->default('active')
                ->after('next_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_sales', function (Blueprint $table) {
            $table->dropColumn('total_amount');
            $table->dropColumn('paid_amount');
            $table->dropColumn('payment_required');
            $table->dropColumn('status');
        });
    }
}
