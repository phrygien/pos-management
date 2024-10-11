<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariation>
 */
class ProductVariationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'color' => $this->faker->colorName,
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'barcode' => $this->faker->ean13,
            'image' => $this->faker->imageUrl(640, 480, 'products', true, 'product_variation'),
            'stock_quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
