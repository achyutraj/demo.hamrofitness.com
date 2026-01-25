<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BodyMeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => 1,
            'entry_date' => $this->faker->dateTimeAD(Carbon::now()),
            'height_feet' => 5,
            'height_inches' => 5,
            'weight' => 50,
            'chest' => 35,
            'waist' => 35,
            'hip' => 35,
            'arms' => 20,
        ];
    }
}
