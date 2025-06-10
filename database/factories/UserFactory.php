<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // password
            'profile_picture' => $this->faker->imageUrl(),
            'bio' => $this->faker->paragraph(),
            'location' => json_encode([
                'street' => $this->faker->streetAddress,
                'latitude' => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
            ]),
            'language' => 'en',
            'email_verified_at' => now(),
        ];
    }
}
