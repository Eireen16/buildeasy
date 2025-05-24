<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category' => 'Cement', 'sub_category' => 'Portland Cement'],
            ['category' => 'Cement', 'sub_category' => 'White Cement'],
            ['category' => 'Bricks', 'sub_category' => 'Clay Bricks'],
            ['category' => 'Bricks', 'sub_category' => 'Fly Ash Bricks'],
            ['category' => 'Paint', 'sub_category' => 'Acrylic Paint'],
            ['category' => 'Paint', 'sub_category' => 'Oil-Based Paint'],
            ['category' => 'Steel', 'sub_category' => 'TMT Bars'],
            ['category' => 'Steel', 'sub_category' => 'Mild Steel Rods'],
            ['category' => 'Wood', 'sub_category' => 'Plywood'],
            ['category' => 'Wood', 'sub_category' => 'Hardwood'],
        ];

        DB::table('categories')->insert($categories);
    }
}

