<?php

namespace Database\Seeders\Product;

use App\Models\Product\Product;
use App\Models\Product\Review;
use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $users = User::all();

        foreach ($products as $product) {
            // Create reviews by registered users
            foreach ($users as $user) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'contact' => $user->email, // Assuming users have email
                    'review' => 'This is a sample review by a registered user.',
                    'rating' => rand(1, 5),
                    'status' => 'accepted',
                ]);
            }

            // Create reviews by unregistered users
            for ($i = 0; $i < 5; $i++) {
                Review::create([
                    'product_id' => $product->id,
                    'name' => 'Unregistered',
                    'surname' => 'User ' . $i,
                    'contact' => 'unregistered' . $i . '@example.com',
                    'review' => 'This is a sample review by an unregistered user.',
                    'rating' => rand(1, 5),
                    'status' => 'accepted',
                ]);
            }
        }
    }
}
