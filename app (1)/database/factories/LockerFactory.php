<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LockerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'locker_num' => 'L-'.$this->faker->numberBetween(10,20),
            'status' => $this->faker->randomElement(['available','reserved','switch','maintenance','destroy','repaired']),
            'locker_category_id' => 1,
            'detail_id' => 1,
        ];
    }
}
