<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 * 
 * Factory to test query speed with many translated records
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Names list array for name field
        $foodNames = [
            'Burger',
            'Pizza',
            'Pasta',
            'Sushi',
            'Tacos',
            'Salad',
            'Hot Dog',
            'Sandwich',
            'Fries',
            'Steak',
            'Curry',
            'Burrito',
            'Noodles',
            'Dumplings',
            'Bagel',
            'Soup',
            'Wrap',
            'Chili',
            'Barbecue',
            'Waffle'
        ];

        // Define the data
        return [
            'name' => $this->faker->randomElement(array_merge($foodNames, $this->faker->words(2))),
            'reference_price' => $this->faker->randomFloat(2, 0.1, 1000),
        ];
    }
}
