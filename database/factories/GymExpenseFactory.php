<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GymExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'detail_id' => 1,
            'item_name' => $this->faker->numberBetween(1,16),
            'category_id' => $this->faker->numberBetween(1,16),
            'purchase_date' => '2023-02-10',
            'price' => $this->faker->numberBetween(3000,15000),
        ];
    }
}
