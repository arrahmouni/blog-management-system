<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(! User::where('email', 'admin@example.com')->exists()) {
            User::factory()->admin()->hasPosts(5)->create([
                'email' => 'admin@example.com',
            ]);
        }

        if(! User::where('email', 'writer@example.com')->exists()) {
            User::factory()->writer()->hasPosts(5)->create([
                'email' => 'writer@example.com',
            ]);
        }

        if(! User::where('email', 'user@example.com')->exists()) {
            User::factory()->user()->create([
                'email' => 'user@example.com',
            ]);
        }
    }
}
