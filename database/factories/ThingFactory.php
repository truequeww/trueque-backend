<?php
namespace Database\Factories;

use App\Models\Thing;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Material;
use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Providers\ProductProvider;

class ThingFactory extends Factory
{
    protected $model = Thing::class;

    public function definition()
    {
        $this->faker->addProvider(new ProductProvider($this->faker));

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->productName(),
            'description' => $this->faker->productDescription(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'condition_id' => Condition::factory(),
            'availability' => $this->faker->boolean,
            'weight' => $this->faker->randomFloat(2, 0.1, 100),
            'category_id' => Category::factory(),
            'material_id' => Material::factory(),
            'color_id' => Color::factory(),
            'location' => json_encode([
                'street' => $this->faker->streetAddress,
                'latitude' => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
            ]),
            'imagesUrl' => $this->faker->imageUrl(),
        ];
    }
}
