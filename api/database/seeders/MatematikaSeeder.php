<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatematikaSeeder extends Seeder
{
    public function run(): void
    {
        $matekId = DB::table('subjects')
            ->where('name', 'Matematika')
            ->value('id');

        $questions = [
            [
                'question' => 'Mennyi 15%-a a 200-nak?',
                'answers' => [
                    ['answer' => '25', 'is_correct' => false],
                    ['answer' => '30', 'is_correct' => true],
                    ['answer' => '35', 'is_correct' => false],
                    ['answer' => '40', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a területét képező egyenes szögben álló trapéznak, ha a lábai 5 és 8, a magassága 6?',
                'answers' => [
                    ['answer' => '39', 'is_correct' => false],
                    ['answer' => '39 négyzetméter', 'is_correct' => true],
                    ['answer' => '65', 'is_correct' => false],
                    ['answer' => '6', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Hány 36-tal osztható szám van 100 és 200 között?',
                'answers' => [
                    ['answer' => '3', 'is_correct' => false],
                    ['answer' => '4', 'is_correct' => true],
                    ['answer' => '5', 'is_correct' => false],
                    ['answer' => '6', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $qData) {

            // QUESTION
            DB::table('questions')->updateOrInsert(
                [
                    'subject_id' => $matekId,
                    'question'   => $qData['question'],
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $questionId = DB::table('questions')
                ->where('subject_id', $matekId)
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
