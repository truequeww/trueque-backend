<?php

namespace Database\Factories;

use App\Models\OfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferStatusFactory extends Factory
{
    protected $model = OfferStatus::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
