<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core data first

            SubjectSeeder::class,
            MagyarIrodalomSeeder::class,
            MatematikaSeeder::class,
            TortenelemSeeder::class,

            // Game system
            GameSystemSeeder::class,

            // Users and progress
            UsersAndProgressSeeder::class,

            // Matches and practice (depends on users and questions)
            MatchesAndPracticeSeeder::class,

            // Reports (depends on everything else)
            ReportsSeeder::class,
        ]);
    }
}
