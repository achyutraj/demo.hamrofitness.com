<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'detail_id' => 3,
            'category_id' => $this->faker->numberBetween(1,9),
            'purchase_date' => '2023-02-10',
            'price' => $this->faker->numberBetween(3000,15000),
        ];
    }
}
