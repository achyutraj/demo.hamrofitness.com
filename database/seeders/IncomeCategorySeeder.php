<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            // 'Event/Space Rent', 'Advertisement Placement', 'Parking', 'Product Sponsors',
            // 'Entry fee','Competition Prize','Diet Plan','Online Training', 'Seminars',
            'Swimming','Futsal','Badminton','Cafe','Swimming Training','Others'
        ];

        foreach ($categories as $category) {
            IncomeCategory::create([
                'title' => $category,
            ]);
        }
    }
}
