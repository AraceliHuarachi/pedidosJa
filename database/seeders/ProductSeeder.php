<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Bread', 'reference_price' => 0.50],
            ['name' => 'Chips', 'reference_price' => 30.00],
            ['name' => 'Cheese roll', 'reference_price' => 28.00],
            ['name' => 'Fruit salad', 'reference_price' => 6.00],
            ['name' => 'Chicken sandwich', 'reference_price' => 6.00],
        ];

        foreach ($products as $product) {
            Product::create($product); // Cambia aquí para usar el modelo Product
        }
    }
}
