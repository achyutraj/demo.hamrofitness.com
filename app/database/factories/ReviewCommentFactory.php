<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'read_review' => 1,
            'comment' => $this->faker->sentence(),
            'merchant_id' => 2,
            'review_id' => 1,
            'client_id' => 6096,
        ];
    }
}
