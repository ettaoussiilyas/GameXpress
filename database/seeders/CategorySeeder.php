<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Gaming Laptops',
            'Gaming PCs',
            'Gaming Monitors',
            'Gaming Keyboards',
            'Gaming Mice',
            'Gaming Headsets',
            'Gaming Chairs',
            'Console Gaming',
            'PC Components',
            'Gaming Accessories',
            'Virtual Reality',
            'Gaming Software',
            'Network Gaming',
            'Gaming Storage',
            'Streaming Gear'
        ];

        foreach ($categories as $category) {
            Categorie::create([
                'name' => $category,
                'slug' => Str::slug($category)
            ]);
        }

        // Add some random categories using Faker
        for ($i = 0; $i < 5; $i++) {
            $name = fake()->unique()->words(2, true);
            Categorie::create([
                'name' => ucwords($name),
                'slug' => Str::slug($name)
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
