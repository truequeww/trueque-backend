<?php
namespace Database\Factories;

use App\Models\Dislike;
use App\Models\User;
use App\Models\Thing;
use Illuminate\Database\Eloquent\Factories\Factory;

class DislikeFactory extends Factory
{
    protected $model = Dislike::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'thing_id' => Thing::factory(),
        ];
    }
}
