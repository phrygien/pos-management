<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Store;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'store_id' => Store::factory(),
            'unit_id' => Unit::factory(),
            'barcode' => $this->faker->ean13,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'image' => $this->faker->imageUrl(640, 480, 'products', true, 'product'),
        ];
    }
}
