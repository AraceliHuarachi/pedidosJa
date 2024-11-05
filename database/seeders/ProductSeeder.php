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
            ['name' => 'Salteña', 'reference_price' => 8.00],
            ['name' => 'Papitas', 'reference_price' => 30.00],
            ['name' => 'Rollo de queso', 'reference_price' => 25.00],
            ['name' => 'Ensalada de Frutas', 'reference_price' => 6.00],
            ['name' => 'Sandwich de palta', 'reference_price' => 6.00],
        ];

        foreach ($products as $product) {
            Product::create($product); // Cambia aquí para usar el modelo Product
        }
    }
}
