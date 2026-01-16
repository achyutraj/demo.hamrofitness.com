<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'review_text' => $this->faker->sentence(),
            'status' =>'approved',
            'detail_id' => 1,
            'client_id' => 6096,
        ];
    }
}
