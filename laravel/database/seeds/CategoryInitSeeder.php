<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("TRUNCATE `categories`");

        $jsonData = Storage::get('private/category-import.json');
        $jsonData = json_decode($jsonData, true);

        foreach ($jsonData as $categoryL1) {
            $cL1        = new Category();
            $cL1->level = 1;
            $cL1->name  = $categoryL1['name'];
            $cL1->save();

            foreach ($categoryL1['sub'] as $categoryL2) {
                $cL2            = new Category();
                $cL2->parent_id = $cL1->id;
                $cL2->level     = 2;
                $cL2->name      = $categoryL2['name'];
                $cL2->save();
            }
        }
    }
}
