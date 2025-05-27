<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class CategorySeeder extends Seeder
{
    public function run()
{
    $transportas = Category::create([
        'name' => 'Transportas',
        'type' => -1 // išlaidos
    ]);
    Subcategory::create(['name' => 'Kuras', 'category_id' => $transportas->id]);
    Subcategory::create(['name' => 'Remontas', 'category_id' => $transportas->id]);

    $maistas = Category::create([
        'name' => 'Maistas',
        'type' => -1 // išlaidos
    ]);
    Subcategory::create(['name' => 'Pietūs', 'category_id' => $maistas->id]);
    Subcategory::create(['name' => 'Pusryčiai', 'category_id' => $maistas->id]);

    $pramogos = Category::create([
        'name' => 'Pramogos',
        'type' => -1 // išlaidos
    ]);
    Subcategory::create(['name' => 'Kinas', 'category_id' => $pramogos->id]);
}

}
