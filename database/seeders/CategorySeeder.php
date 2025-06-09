<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Pajamos
            ['name' => 'Individuali veikla', 'type' => 1],
            ['name' => 'Darbas pagal darbo sutartį', 'type' => 1],
            ['name' => 'Kitos pajamos', 'type' => 1],

            // Išlaidos
            ['name' => 'Transportas', 'type' => -1],
            ['name' => 'Maistas', 'type' => -1],
            ['name' => 'Kitos išlaidos', 'type' => -1],
            ['name' => 'Laisvalaikis ir pramogos', 'type' => -1],
            ['name' => 'Pirkiniai ir paslaugos', 'type' => -1],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
