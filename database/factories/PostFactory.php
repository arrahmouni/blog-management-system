<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => User::factory()->writer(),
            'title'         => fake()->sentence(3),
            'body'          => fake()->paragraph(3),
            'is_published'  => fake()->boolean(75),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            $randomCategories = Category::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $post->categories()->sync($randomCategories);

            $randomImagePath = public_path('images/fake/'. rand(1, 22) .'.png');
            $post->published_at = $post->is_published ? now() : null;
            $post->save();

            $post->addMedia($randomImagePath)
            ->preservingOriginal()
            ->toMediaCollection(Post::MEDIA_COLLECTION);
        });
    }
}
