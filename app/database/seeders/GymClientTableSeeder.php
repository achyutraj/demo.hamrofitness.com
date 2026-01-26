<?php

namespace Database\Seeders;

use App\Models\GymClient;
use Illuminate\Database\Seeder;

class GymClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GymClient::factory(1000)->create();
    }
}
