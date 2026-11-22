<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $names = [
        'Books and stationery',
        'Clothes and shoes',
        'Electronics and phones',
        'Food and snacks',
        'Hostel and household items',
        'Services',
        'Other',
    ];

    foreach ($names as $name) {
        Category::firstOrCreate(['name' => $name]);
    }
}
}
