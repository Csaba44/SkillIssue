<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagyarIrodalomSeeder extends Seeder
{
    public function run(): void
    {
        $magyarId = DB::table('subjects')
            ->where('name', 'Magyar nyelv és irodalom')
            ->value('id');

        $questions = [
            [
                'question' => 'Melyik a legrégebbi magyar nyelvemlék?',
                'answers' => [
                    ['answer' => 'Leuveni krónika', 'is_correct' => false],
                    ['answer' => 'Halotti beszéd', 'is_correct' => true],
                    ['answer' => 'Kölcsey Himnusza', 'is_correct' => false],
                    ['answer' => 'Jókai Mór novellái', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta az Az ember tragédiája című művet?',
                'answers' => [
                    ['answer' => 'Shakespeare', 'is_correct' => false],
                    ['answer' => 'Madách Imre', 'is_correct' => true],
                    ['answer' => 'Petőfi Sándor', 'is_correct' => false],
                    ['answer' => 'Arany János', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ady Endre melyik versében szerepel a „Góg és Magóg fia vagyok” sor?',
                'answers' => [
                    ['answer' => 'Kocsi kávéházban', 'is_correct' => false],
                    ['answer' => 'Góg és Magóg fia vagyok', 'is_correct' => true],
                    ['answer' => 'A magyar Ugar', 'is_correct' => false],
                    ['answer' => 'Léda asszony emlékére', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $qData) {

            // QUESTION – updateOrInsert
            DB::table('questions')->updateOrInsert(
                [
                    'subject_id' => $magyarId,
                    'question'   => $qData['question'],
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $questionId = DB::table('questions')
                ->where('subject_id', $magyarId)
                ->where('question', $qData['question'])
                ->value('id');

            // ANSWERS
            foreach ($qData['answers'] as $answer) {
                DB::table('answers')->updateOrInsert(
                    [
                        'question_id' => $questionId,
                        'answer'      => $answer['answer'],
                    ],
                    [
                        'is_correct'  => $answer['is_correct'],
                        'updated_at'  => now(),
                        'created_at'  => now(),
                    ]
                );
            }
        }
    }
}
