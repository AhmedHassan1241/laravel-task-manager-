<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id'=>User::inRandomOrder()->first()->id,
            'phone'=>fake()->phoneNumber(),
            'address'=>fake()->address(),
            'bio'=>fake()->paragraph(),
            'image'=>fake()->imageUrl(640,480,'people')
        ];
    }
}
