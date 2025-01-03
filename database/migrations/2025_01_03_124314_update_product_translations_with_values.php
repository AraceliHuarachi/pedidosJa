<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fill the array that will be converted to json with the translations of the names
        $translations = [
            1 => ['es' => ['name' => 'Pan']],
            2 => ['es' => ['name' => 'Papas Fritas']],
            3 => ['es' => ['name' => 'Rollo de Queso']],
            4 => ['es' => ['name' => 'Ensalada de Frutas']],
            5 => ['es' => ['name' => 'Sandwich de Pollo']]
        ];

        foreach ($translations as $id => $translation) {
            DB::table('products')
                ->where('id', $id)
                ->update(['translations' => json_encode($translation)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If you want to revert the changes, delete the translations
        DB::table('products')->update(['translations' => null]);
    }
};
