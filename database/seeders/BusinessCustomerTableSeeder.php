<?php

namespace Database\Seeders;

use App\Models\BusinessCustomer;
use Illuminate\Database\Seeder;

class BusinessCustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1 ;$i <= 15;$i++) {
            BusinessCustomer::create([
                'customer_id' => rand(94,1000),
                'detail_id' => 3
            ]);
        }
    }
}
