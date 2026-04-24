<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\CategorySeederService;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'vofforila@gmail.com'],
            [
                'name' => 'Paul Berciu',
                'password' => bcrypt('gameing123'),
            ]
        );

        if ($user->wasRecentlyCreated) {
            app(CategorySeederService::class)->seedDefaultCategories($user);
        }
    }


}
