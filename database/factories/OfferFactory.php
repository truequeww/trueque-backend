<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\User;
use App\Models\Chat;
use App\Models\OfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition()
    {
        return [
            'chat_id' => Chat::factory(),
            'from_user_id' => User::factory(),
            'to_user_id' => User::factory(),
            'cash_value_offered' => $this->faker->randomFloat(2, 1, 1000),
            'cash_value_requested' => $this->faker->randomFloat(2, 1, 1000),
            'status_id' => OfferStatus::factory(),
        ];
    }
}
