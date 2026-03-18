<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IrodalomSeeder extends Seeder
{
    public function run(): void
    {
        $subjectId = DB::table('subjects')
            ->where('name', 'Irodalom')
            ->value('id');

        $questions = [
            [
                'question' => 'Melyik Petőfi Sándor leghíresebb forradalmi verse?',
                'answers' => [
                    ['answer' => 'Nemzeti dal*', 'is_correct' => true],
                    ['answer' => 'János vitéz', 'is_correct' => false],
                    ['answer' => 'Az apostol', 'is_correct' => false],
                    ['answer' => 'Szeptember végén', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor született Petőfi Sándor?',
                'answers' => [
                    ['answer' => '1823*', 'is_correct' => true],
                    ['answer' => '1817', 'is_correct' => false],
                    ['answer' => '1830', 'is_correct' => false],
                    ['answer' => '1819', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik műfajba tartozik a János vitéz?',
                'answers' => [
                    ['answer' => 'Elbeszélő költemény*', 'is_correct' => true],
                    ['answer' => 'Dráma', 'is_correct' => false],
                    ['answer' => 'Lírai vers', 'is_correct' => false],
                    ['answer' => 'Regény', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta a Toldi-trilógiát?',
                'answers' => [
                    ['answer' => 'Arany János*', 'is_correct' => true],
                    ['answer' => 'Petőfi Sándor', 'is_correct' => false],
                    ['answer' => 'Vörösmarty Mihály', 'is_correct' => false],
                    ['answer' => 'Jókai Mór', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Arany János balladája szól egy gyilkos anyáról?',
                'answers' => [
                    ['answer' => 'Ágnes asszony*', 'is_correct' => true],
                    ['answer' => 'Szondi két apródja', 'is_correct' => false],
                    ['answer' => 'V. László', 'is_correct' => false],
                    ['answer' => 'A walesi bárdok', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Vörösmarty Mihály leghíresebb hazafias verse?',
                'answers' => [
                    ['answer' => 'Szózat*', 'is_correct' => true],
                    ['answer' => 'Csongor és Tünde', 'is_correct' => false],
                    ['answer' => 'Vén cigány', 'is_correct' => false],
                    ['answer' => 'Zalán futása', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik irodalmi korszakhoz tartozik Csokonai Vitéz Mihály?',
                'answers' => [
                    ['answer' => 'Felvilágosodás*', 'is_correct' => true],
                    ['answer' => 'Romantika', 'is_correct' => false],
                    ['answer' => 'Realizmus', 'is_correct' => false],
                    ['answer' => 'Barokk', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta az "Egri csillagok" című regényt?',
                'answers' => [
                    ['answer' => 'Gárdonyi Géza*', 'is_correct' => true],
                    ['answer' => 'Jókai Mór', 'is_correct' => false],
                    ['answer' => 'Mikszáth Kálmán', 'is_correct' => false],
                    ['answer' => 'Móricz Zsigmond', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Jókai Mór leghíresebb regénye?',
                'answers' => [
                    ['answer' => 'Az arany ember*', 'is_correct' => true],
                    ['answer' => 'Bánk bán', 'is_correct' => false],
                    ['answer' => 'Fenn az ernyő, nincsen kas', 'is_correct' => false],
                    ['answer' => 'Légy jó mindhalálig', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta a Bánk bán című drámát?',
                'answers' => [
                    ['answer' => 'Katona József*', 'is_correct' => true],
                    ['answer' => 'Madách Imre', 'is_correct' => false],
                    ['answer' => 'Vörösmarty Mihály', 'is_correct' => false],
                    ['answer' => 'Arany János', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta Az ember tragédiája című drámai költeményt?',
                'answers' => [
                    ['answer' => 'Madách Imre*', 'is_correct' => true],
                    ['answer' => 'Katona József', 'is_correct' => false],
                    ['answer' => 'Vörösmarty Mihály', 'is_correct' => false],
                    ['answer' => 'Arany János', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik folyóirat volt a Nyugat mozgalom lapja?',
                'answers' => [
                    ['answer' => 'Nyugat*', 'is_correct' => true],
                    ['answer' => 'Élet és Irodalom', 'is_correct' => false],
                    ['answer' => 'Magyar Csillag', 'is_correct' => false],
                    ['answer' => 'Uránia', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt a Nyugat folyóirat egyik legjelentősebb költője?',
                'answers' => [
                    ['answer' => 'Ady Endre*', 'is_correct' => true],
                    ['answer' => 'Petőfi Sándor', 'is_correct' => false],
                    ['answer' => 'Arany János', 'is_correct' => false],
                    ['answer' => 'Vörösmarty Mihály', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Ady Endre verseskötete az első jelentős szimbolista kötete?',
                'answers' => [
                    ['answer' => 'Új versek*', 'is_correct' => true],
                    ['answer' => 'Vér és arany', 'is_correct' => false],
                    ['answer' => 'Az Illés szekerén', 'is_correct' => false],
                    ['answer' => 'A Halál rokona', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik irodalmi irányzathoz kapcsolható Babits Mihály?',
                'answers' => [
                    ['answer' => 'Nyugatos modernizmus*', 'is_correct' => true],
                    ['answer' => 'Romantika', 'is_correct' => false],
                    ['answer' => 'Realizmus', 'is_correct' => false],
                    ['answer' => 'Szürrealizmus', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta a "Légy jó mindhalálig" című regényt?',
                'answers' => [
                    ['answer' => 'Móricz Zsigmond*', 'is_correct' => true],
                    ['answer' => 'Babits Mihály', 'is_correct' => false],
                    ['answer' => 'Kosztolányi Dezső', 'is_correct' => false],
                    ['answer' => 'Gárdonyi Géza', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Kosztolányi Dezső legismertebb regénye?',
                'answers' => [
                    ['answer' => 'Édes Anna*', 'is_correct' => true],
                    ['answer' => 'Légy jó mindhalálig', 'is_correct' => false],
                    ['answer' => 'Az arany ember', 'is_correct' => false],
                    ['answer' => 'Pacsirta', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik József Attila verse szól a munkásosztály sorsáról?',
                'answers' => [
                    ['answer' => 'A város peremén*', 'is_correct' => true],
                    ['answer' => 'Ódák', 'is_correct' => false],
                    ['answer' => 'Születésnapomra', 'is_correct' => false],
                    ['answer' => 'Mama', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben született József Attila?',
                'answers' => [
                    ['answer' => '1905*', 'is_correct' => true],
                    ['answer' => '1900', 'is_correct' => false],
                    ['answer' => '1910', 'is_correct' => false],
                    ['answer' => '1897', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik műfaj jellemzi Mikszáth Kálmán legtöbb művét?',
                'answers' => [
                    ['answer' => 'Realista kisregény és novella*', 'is_correct' => true],
                    ['answer' => 'Romantikus eposz', 'is_correct' => false],
                    ['answer' => 'Szimbolista líra', 'is_correct' => false],
                    ['answer' => 'Naturalista dráma', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta a "Kőszívű ember fiai" című regényt?',
                'answers' => [
                    ['answer' => 'Jókai Mór*', 'is_correct' => true],
                    ['answer' => 'Mikszáth Kálmán', 'is_correct' => false],
                    ['answer' => 'Móricz Zsigmond', 'is_correct' => false],
                    ['answer' => 'Gárdonyi Géza', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Shakespeare-dráma főhőse dán királyfi?',
                'answers' => [
                    ['answer' => 'Hamlet*', 'is_correct' => true],
                    ['answer' => 'Othello', 'is_correct' => false],
                    ['answer' => 'Macbeth', 'is_correct' => false],
                    ['answer' => 'Lear király', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik antik görög drámaíró műve az Antigoné?',
                'answers' => [
                    ['answer' => 'Szophoklész*', 'is_correct' => true],
                    ['answer' => 'Aiszkhülosz', 'is_correct' => false],
                    ['answer' => 'Euripidész', 'is_correct' => false],
                    ['answer' => 'Arisztophanész', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta a Divine Commedia (Isteni színjáték) című művet?',
                'answers' => [
                    ['answer' => 'Dante Alighieri*', 'is_correct' => true],
                    ['answer' => 'Petrarca', 'is_correct' => false],
                    ['answer' => 'Boccaccio', 'is_correct' => false],
                    ['answer' => 'Homérosz', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik irodalmi korszakhoz tartozik a reneszánsz?',
                'answers' => [
                    ['answer' => '14–16. század*', 'is_correct' => true],
                    ['answer' => '11–13. század', 'is_correct' => false],
                    ['answer' => '17–18. század', 'is_correct' => false],
                    ['answer' => '19. század', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Balassi Bálint leghíresebb versformája?',
                'answers' => [
                    ['answer' => 'Balassi-strófa (9 soros, aabbccbbc rímű)*', 'is_correct' => true],
                    ['answer' => 'Szonett', 'is_correct' => false],
                    ['answer' => 'Hexameter', 'is_correct' => false],
                    ['answer' => 'Alexandrin', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi jellemzi a barokk irodalmat?',
                'answers' => [
                    ['answer' => 'Díszítettség, túlzás, vallásos és hazafias témák*', 'is_correct' => true],
                    ['answer' => 'Egyszerűség, természetközeliség', 'is_correct' => false],
                    ['answer' => 'Társadalomkritika és realizmus', 'is_correct' => false],
                    ['answer' => 'Szimbolizmus és impresszionizmus', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Radnóti Miklós utolsó verseskötete?',
                'answers' => [
                    ['answer' => 'Tajtékos ég*', 'is_correct' => true],
                    ['answer' => 'Újhold', 'is_correct' => false],
                    ['answer' => 'Pogány köszöntő', 'is_correct' => false],
                    ['answer' => 'Járkálj csak, halálraítélt', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik Illyés Gyula leghíresebb verse a zsarnokságról?',
                'answers' => [
                    ['answer' => 'Egy mondat a zsarnokságról*', 'is_correct' => true],
                    ['answer' => 'Puszták népe', 'is_correct' => false],
                    ['answer' => 'Bartók', 'is_correct' => false],
                    ['answer' => 'Koszorú', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki írta a "Tüskevár" és "Kék tündér" ifjúsági regényeket?',
                'answers' => [
                    ['answer' => 'Fekete István*', 'is_correct' => true],
                    ['answer' => 'Gárdonyi Géza', 'is_correct' => false],
                    ['answer' => 'Molnár Ferenc', 'is_correct' => false],
                    ['answer' => 'Tersánszky Józsi Jenő', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $qData) {
            DB::table('questions')->updateOrInsert(
                [
                    'subject_id' => $subjectId,
                    'question'   => $qData['question'],
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $questionId = DB::table('questions')
                ->where('subject_id', $subjectId)
                ->where('question', $qData['question'])
                ->value('id');

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
