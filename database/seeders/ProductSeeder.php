<?php

namespace Database\Seeders;

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
        DB::table('products')->insert([
            [
                'name' => 'Salteña',
                'reference_price' => 8.00,
            ],
            [
                'name' => 'Chips',
                'reference_price' => 30.00,
            ],
            [
                'name' => 'Cheese_Roll',
                'reference_price' => 25.00,
            ],
            [
                'name' => 'Fruit_Salad',
                'reference_price' => 6.00,
            ],
            [
                'name' => 'Avocado_Sandwich',
                'reference_price' => 6.00,
            ],
        ]);
    }
}
