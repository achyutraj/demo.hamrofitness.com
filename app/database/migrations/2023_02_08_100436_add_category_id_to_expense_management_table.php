<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToExpenseManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_management', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
            $table->foreignId('category_id')->after('item_name')->nullable()
                ->constrained('expense_categories')->onUpdate('cascade');
            $table->text('remarks')->nullable()->after('bill');

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
            $table->dropForeign(['category_id']);
            $table->dropColumn(['uuid','category_id','remarks']);
        });
    }
}
