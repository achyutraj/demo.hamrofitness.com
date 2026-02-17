<?php

namespace Database\Seeders;

use App\Models\LockerCategory;
use Illuminate\Database\Seeder;

class LockerCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LockerCategory::factory(7)->create();
    }
}
