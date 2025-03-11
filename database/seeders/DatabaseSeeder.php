<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Movie::factory(100)
            ->has(Rating::factory()->count(500))
            ->create();
    }
}
