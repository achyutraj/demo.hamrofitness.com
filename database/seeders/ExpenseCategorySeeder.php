<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Rent', 'Telephone', 'Electricity', 'Asset Purchase',
            'Printing & Stationary','Advertisement','Repair Maintenance','Internet bills',
            'Food & Beverage','Sales Incentives','Misc Expenses','Government Tax','Legal Expenses',
            'Water bill','Cleaning & Housekeeping',
            'Nutrition Purchase','Refund',
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create([
                'title' => $category,
            ]);
        }
    }
}
