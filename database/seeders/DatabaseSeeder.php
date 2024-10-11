<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Unit;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crée des catégories, des marques, et des magasins
        $categories = Category::factory()->count(5)->create();
        $brands = Brand::factory()->count(5)->create();
        $stores = Store::factory()->count(5)->create();
        $units = Unit::factory()->count(5)->create(); // Crée quelques unités de mesure

        $faker = \Faker\Factory::create(); // Initialiser Faker

        // Boucle pour chaque catégorie, marque et magasin
        foreach ($categories as $category) {
            foreach ($brands as $brand) {
                foreach ($stores as $store) {
                    // Crée 20 produits pour chaque combinaison de catégorie, marque et magasin
                    for ($i = 0; $i < 20; $i++) {
                        // Générer un produit unique
                        $product = Product::create([
                            'name' => $faker->word, // Générer un nom aléatoire pour chaque produit
                            'category_id' => $category->id,
                            'brand_id' => $brand->id,
                            'store_id' => $store->id,
                            'unit_id' => $units->random()->id, // Assigner une unité aléatoire
                            'price' => $faker->randomFloat(2, 1, 100),
                            'barcode' => $this->generateUniqueBarcode(), // Code-barres unique
                            'image' => $faker->imageUrl(640, 480, 'products', true, 'product'), // Générer une image pour chaque produit
                        ]);

                        // Crée 3 variations pour chaque produit
                        for ($j = 0; $j < 3; $j++) {
                            ProductVariation::create([
                                'product_id' => $product->id,
                                'color' => $faker->safeColorName, // Couleur aléatoire
                                'size' => $faker->randomElement(['S', 'M', 'L', 'XL']), // Taille aléatoire
                                'stock_quantity' => $faker->numberBetween(1, 100), // Quantité de stock
                                'barcode' => $this->generateUniqueBarcode(), // Code-barres unique pour la variation
                                'image' => $faker->imageUrl(640, 480, 'products', true, 'product_variation'), // Image pour la variation
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Génère un code-barres unique pour le produit.
     *
     * @return string
     */
    private function generateUniqueBarcode()
    {
        $barcode = '';
        $attempts = 0;

        do {
            $barcode = str_pad(random_int(0, 9999999999999), 13, '0', STR_PAD_LEFT); // Générer un code-barres à 13 chiffres
            $attempts++;
            // Limite les tentatives à 10 pour éviter une boucle infinie
            if ($attempts > 10) {
                throw new \Exception("Unable to generate a unique barcode after multiple attempts.");
            }
        } while (Product::where('barcode', $barcode)->exists() || ProductVariation::where('barcode', $barcode)->exists());

        return $barcode;
    }
}
