<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TortenelemSeeder extends Seeder
{
    public function run(): void
    {
        $tortId = DB::table('subjects')->where('name', 'Történelem')->first()->id;

        $questions = [
            [
                'question' => 'Mikor volt a mohácsi vész?',
                'answers' => [
                    ['answer' => '1526', 'is_correct' => true],
                    ['answer' => '1453', 'is_correct' => false],
                    ['answer' => '1541', 'is_correct' => false],
                    ['answer' => '1686', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'Ki volt Magyarország miniszterelnöke 1956 októberében?',
                'answers' => [
                    ['answer' => 'Nagy Imre', 'is_correct' => true],
                    ['answer' => 'Kádár János', 'is_correct' => false],
                    ['answer' => 'Rákosi Mátyás', 'is_correct' => false],
                    ['answer' => 'Gerő Ernő', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'Melyik király alapította meg a Fekete sereget?',
                'answers' => [
                    ['answer' => 'Mátyás király', 'is_correct' => true],
                    ['answer' => 'Nagy Lajos', 'is_correct' => false],
                    ['answer' => 'Zsigmond', 'is_correct' => false],
                    ['answer' => 'Károly Róbert', 'is_correct' => false]
                ]
            ]
        ];

        foreach ($questions as $qData) {
            $questionId = DB::table('questions')->insertGetId([
                'subject_id' => $tortId,
                'question' => $qData['question'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($qData['answers'] as $answer) {
                DB::table('answers')->insert([
                    'question_id' => $questionId,
                    'answer' => $answer['answer'],
                    'is_correct' => $answer['is_correct'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
