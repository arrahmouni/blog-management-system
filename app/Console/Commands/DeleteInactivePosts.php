<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class DeleteInactivePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-inactive-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete posts that received no comments for 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Searching for inactive posts...');

        $posts = Post::published()
            ->where('published_at', '<=', now()->subDays(7))
            ->doesntHave('comments')
            ->get();

        $count = 0;

        foreach ($posts as $post) {
            $post->delete();
            $count++;
        }

        $this->info('Deleted ' . $count . ' inactive posts.');
    }
}
