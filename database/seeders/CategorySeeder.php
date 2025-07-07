<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Technology',
            'Health & Wellness',
            'Travel & Adventure',
            'Food & Cooking',
            'Personal Finance',
            'Career & Business',
            'Self-Improvement',
            'Entertainment',
            'Sports & Fitness',
            'Science & Nature',
            'Education',
            'Arts & Culture',
            'Politics',
            'Environment',
            'Parenting',
            'Relationships',
            'Fashion & Beauty',
            'Gaming',
            'Books & Literature',
            'Health'
        ];

        foreach ($categories as $category) {
            Category::create([
                'title' => $category,
            ]);
        }
    }

}
