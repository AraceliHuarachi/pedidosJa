<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

/**
 * Add dummy translations to the translations attribute of each product
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $faker = Faker::create();

        $products = DB::table('products')->get();

        foreach ($products as $product) {
            $spanishTranslation = $faker->word();

            $translations = [
                $product->id => [
                    'es' => ['name' => $spanishTranslation]
                ]
            ];

            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'translations' => json_encode($translations[$product->id])
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('products')->update(['translations' => null]);
    }
};
