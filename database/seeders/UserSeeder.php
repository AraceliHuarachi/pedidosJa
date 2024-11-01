<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Ari'],
            ['name' => 'Bruce'],
            ['name' => 'Ivan'],
            ['name' => 'Javi'],
            ['name' => 'Luis'],
            ['name' => 'Mauricio'],
            ['name' => 'Rafa'],
            ['name' => 'Roy']
        ]);
    }
}
