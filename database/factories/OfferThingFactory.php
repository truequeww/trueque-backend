<?php

namespace Database\Factories;

use App\Models\OfferThing;
use App\Models\Offer;
use App\Models\Thing;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferThingFactory extends Factory
{
    protected $model = OfferThing::class;

    public function definition()
    {
        return [
            'offer_id' => Offer::factory(),
            'thing_id' => Thing::factory(),
            'is_offered' => $this->faker->boolean,
        ];
    }
}
