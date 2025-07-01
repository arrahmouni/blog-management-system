<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            array_merge(
                User::factory()->admin()->active()->make()->toArray(),
                ['password' => 'password123', 'email' => 'admin@example.com']
            )
        );

        User::firstOrCreate(
            ['email' => 'writer@example.com'],
            array_merge(
                User::factory()->writer()->active()->make()->toArray(),
                ['password' => 'password123', 'email' => 'writer@example.com']
            )
        );

        User::firstOrCreate(
            ['email' => 'user@example.com'],
            array_merge(
                User::factory()->user()->active()->make()->toArray(),
                ['password' => 'password123', 'email' => 'user@example.com']
            )
        );

        // These 10 will always be newly created
        User::factory(5)->create();
    }
}
