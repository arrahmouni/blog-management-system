<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => 1,
            'post_id'       => Post::factory(),
            'body'          => fake()->text(50),
            'is_accepted'   => fake()->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Comment $comment) {
            $comment->user_id     = User::inRandomOrder()->first()->id;
            $comment->save();
            $comment->is_accepted = $comment->user->isAdmin() ? true : fake()->boolean();
            $comment->save();
        });
    }
}
