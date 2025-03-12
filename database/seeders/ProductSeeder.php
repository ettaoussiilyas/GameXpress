<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // First ensure we have some categories
        $categories = Categorie::all();

        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please run CategorySeeder first.');
            return;
        }

        // Create 50 sample products
        for ($i = 0; $i < 50; $i++) {
            $name = fake()->words(3, true);

            Product::create([
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
                'price' => fake()->randomFloat(2, 10, 1000),
                'stock' => fake()->numberBetween(0, 100),
                'status' => fake()->randomElement(['active', 'inactive']),
                'category_id' => $categories->random()->id,
            ]);
        }

        // Create some specific products for testing
        $testProducts = [
            [
                'name' => 'Gaming Laptop XPS 15',
                'price' => 1299.99,
                'stock' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Gaming Mouse Pro',
                'price' => 89.99,
                'stock' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'price' => 159.99,
                'stock' => 30,
                'status' => 'active',
            ],
        ];

        foreach ($testProducts as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'price' => $product['price'],
                'stock' => $product['stock'],
                'status' => $product['status'],
                'category_id' => $categories->random()->id,
            ]);
        }

        $this->command->info('Products seeded successfully!');
    }
}
