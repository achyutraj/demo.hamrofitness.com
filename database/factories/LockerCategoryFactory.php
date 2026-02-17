<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LockerCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'Locker -'.$this->faker->title,
            'price' => $this->faker->numberBetween(1000,3000),
            'duration' => $this->faker->numberBetween(1,3),
            'duration_type' => $this->faker->randomElement(['days','month','year']),
            'type' => $this->faker->randomElement(['small','medium','large']),
            'detail_id' => 1,
        ];
    }
}
