<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Listing;
use App\Models\User;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    // the first account in the users table (the one you registered)
    $user = User::first();

    // title, description, price, category name
    $items = [
        ['Business Law textbook', 'Good condition, no missing pages.', 25000, 'Books and stationery'],
        ['Laptop charger (HP)', 'Original charger, works well.', 40000, 'Electronics and phones'],
        ['Black school shoes size 42', 'Worn twice, very clean.', 30000, 'Clothes and shoes'],
        ['Hostel bedsheet set', 'Cotton, washed and ready.', 20000, 'Hostel and household items'],
        ['Printing and binding', 'Fast service near the library.', 500, 'Services'],
    ];

    foreach ($items as [$title, $description, $price, $categoryName]) {
        Listing::create([
            'user_id'     => $user->id,
            // find the category by its name and use its id
            'category_id' => Category::where('name', $categoryName)->first()->id,
            'title'       => $title,
            'description' => $description,
            'price'       => $price,
            // status and condition use the defaults from the migration
        ]);
    }
}
}
