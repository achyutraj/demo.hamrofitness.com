<?php

namespace Database\Factories;

use App\Models\GymClient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GymClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GymClient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => '',
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'gender' => $this->faker->randomElement(['male','female']),
            'dob' => 1995-01-01,
            'mobile' => $this->faker->numerify('##########'),
            'address' => $this->faker->address,
        ];
    }
}
