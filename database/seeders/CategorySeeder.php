<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Cement' => ['Portland Cement', 'White Cement', 'Quick Setting Cement', 'Sulfate Resistant Cement'],
            'Steel' => ['Rebar', 'Steel Beams', 'Steel Sheets', 'Steel Pipes', 'Steel Wire'],
            'Bricks' => ['Clay Bricks', 'Concrete Bricks', 'Fire Bricks', 'Fly Ash Bricks'],
            'Sand' => ['River Sand', 'M-Sand', 'P-Sand', 'Robo Sand'],
            'Tools' => ['Hand Tools', 'Power Tools', 'Measuring Tools', 'Safety Equipment'],
            'Paint' => ['Interior Paint', 'Exterior Paint', 'Primer', 'Wood Stain'],
            'Tiles' => ['Floor Tiles', 'Wall Tiles', 'Roof Tiles', 'Ceramic Tiles'],
            'Wood' => ['Timber', 'Plywood', 'MDF Boards', 'Particle Boards']
        ];

        foreach ($categories as $categoryName => $subCategories) {
            $category = Category::create(['category' => $categoryName]);
            
            foreach ($subCategories as $subCategoryName) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'subcategory' => $subCategoryName
                ]);
            }
        }
    }
}

