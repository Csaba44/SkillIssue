<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Magyar nyelv és irodalom',
            'Matematika',
            'Történelem',
            'Angol nyelv',
            'Biológia',
            'Fizika',
            'Kémia'
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->updateOrInsert(
                ['name' => $subject],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
