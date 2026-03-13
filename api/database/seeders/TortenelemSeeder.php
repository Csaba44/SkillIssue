<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TortenelemSeeder extends Seeder
{
    public function run(): void
    {
        $tortId = DB::table('subjects')
            ->where('name', 'Történelem')
            ->value('id');

        $questions = [
            [
                'question' => 'Mikor volt a mohácsi vész?',
                'answers' => [
                    ['answer' => '1526*', 'is_correct' => true],
                    ['answer' => '1453', 'is_correct' => false],
                    ['answer' => '1541', 'is_correct' => false],
                    ['answer' => '1686', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki vezette a törököket a mohácsi csatában?',
                'answers' => [
                    ['answer' => 'I. Szulejmán*', 'is_correct' => true],
                    ['answer' => 'II. Mehmed', 'is_correct' => false],
                    ['answer' => 'I. Szelim', 'is_correct' => false],
                    ['answer' => 'Murád szultán', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben foglalták el a törökök Budát?',
                'answers' => [
                    ['answer' => '1541*', 'is_correct' => true],
                    ['answer' => '1526', 'is_correct' => false],
                    ['answer' => '1566', 'is_correct' => false],
                    ['answer' => '1552', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt Magyarország első királya?',
                'answers' => [
                    ['answer' => 'I. István*', 'is_correct' => true],
                    ['answer' => 'Géza fejedelem', 'is_correct' => false],
                    ['answer' => 'I. László', 'is_correct' => false],
                    ['answer' => 'Koppány', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben koronázták királlyá I. Istvánt?',
                'answers' => [
                    ['answer' => '1000*', 'is_correct' => true],
                    ['answer' => '896', 'is_correct' => false],
                    ['answer' => '972', 'is_correct' => false],
                    ['answer' => '1038', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik csatában szenvedtek vereséget a magyarok 955-ben?',
                'answers' => [
                    ['answer' => 'Augsburgi csata (Lech-mező)*', 'is_correct' => true],
                    ['answer' => 'Pozsonyi csata', 'is_correct' => false],
                    ['answer' => 'Rigómezei csata', 'is_correct' => false],
                    ['answer' => 'Muhi csata', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben volt a tatárjárás Magyarországon?',
                'answers' => [
                    ['answer' => '1241–1242*', 'is_correct' => true],
                    ['answer' => '1301–1302', 'is_correct' => false],
                    ['answer' => '1222–1223', 'is_correct' => false],
                    ['answer' => '1285–1286', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit jelent az Aranybulla (1222)?',
                'answers' => [
                    ['answer' => 'A nemesek jogait rögzítő oklevél*', 'is_correct' => true],
                    ['answer' => 'A jobbágyok felszabadítását kimondó törvény', 'is_correct' => false],
                    ['answer' => 'Az egyház és állam szétválasztásáról szóló rendelet', 'is_correct' => false],
                    ['answer' => 'Kereskedelmi szerződés a Német-római Birodalommal', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt Hunyadi János fia, aki később Magyarország királya lett?',
                'answers' => [
                    ['answer' => 'Mátyás király*', 'is_correct' => true],
                    ['answer' => 'László', 'is_correct' => false],
                    ['answer' => 'János', 'is_correct' => false],
                    ['answer' => 'Ulászló', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben volt a nándorfehérvári diadal?',
                'answers' => [
                    ['answer' => '1456*', 'is_correct' => true],
                    ['answer' => '1444', 'is_correct' => false],
                    ['answer' => '1463', 'is_correct' => false],
                    ['answer' => '1521', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik dinasztia tagja volt Károly Róbert?',
                'answers' => [
                    ['answer' => 'Anjou*', 'is_correct' => true],
                    ['answer' => 'Árpád', 'is_correct' => false],
                    ['answer' => 'Habsburg', 'is_correct' => false],
                    ['answer' => 'Jagello', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor ért véget a török hódoltság Magyarországon?',
                'answers' => [
                    ['answer' => '1699 (karlócai béke)*', 'is_correct' => true],
                    ['answer' => '1686', 'is_correct' => false],
                    ['answer' => '1718', 'is_correct' => false],
                    ['answer' => '1711', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki vezette a Rákóczi-szabadságharcot (1703–1711)?',
                'answers' => [
                    ['answer' => 'II. Rákóczi Ferenc*', 'is_correct' => true],
                    ['answer' => 'Thököly Imre', 'is_correct' => false],
                    ['answer' => 'Zrínyi Miklós', 'is_correct' => false],
                    ['answer' => 'Bocskai István', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben született Széchenyi István?',
                'answers' => [
                    ['answer' => '1791*', 'is_correct' => true],
                    ['answer' => '1802', 'is_correct' => false],
                    ['answer' => '1783', 'is_correct' => false],
                    ['answer' => '1809', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit nevezünk a "legnagyobb magyarnak"?',
                'answers' => [
                    ['answer' => 'Széchenyi Istvánt*', 'is_correct' => true],
                    ['answer' => 'Kossuth Lajost', 'is_correct' => false],
                    ['answer' => 'Deák Ferencet', 'is_correct' => false],
                    ['answer' => 'Petőfi Sándort', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor tört ki az 1848-as magyar forradalom?',
                'answers' => [
                    ['answer' => 'Március 15.*', 'is_correct' => true],
                    ['answer' => 'Március 3.', 'is_correct' => false],
                    ['answer' => 'Február 24.', 'is_correct' => false],
                    ['answer' => 'Április 11.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt az 1848–49-es forradalom és szabadságharc fővezére a végső szakaszban?',
                'answers' => [
                    ['answer' => 'Görgei Artúr*', 'is_correct' => true],
                    ['answer' => 'Kossuth Lajos', 'is_correct' => false],
                    ['answer' => 'Bem József', 'is_correct' => false],
                    ['answer' => 'Damjanich János', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben kötötték az Osztrák–Magyar kiegyezést?',
                'answers' => [
                    ['answer' => '1867*', 'is_correct' => true],
                    ['answer' => '1849', 'is_correct' => false],
                    ['answer' => '1873', 'is_correct' => false],
                    ['answer' => '1860', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt a kiegyezés magyar főtárgyalója?',
                'answers' => [
                    ['answer' => 'Deák Ferenc*', 'is_correct' => true],
                    ['answer' => 'Andrássy Gyula', 'is_correct' => false],
                    ['answer' => 'Tisza Kálmán', 'is_correct' => false],
                    ['answer' => 'Eötvös József', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben tört ki az első világháború?',
                'answers' => [
                    ['answer' => '1914*', 'is_correct' => true],
                    ['answer' => '1912', 'is_correct' => false],
                    ['answer' => '1916', 'is_correct' => false],
                    ['answer' => '1918', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik békeszerződés zárta le az első világháborút Magyarország számára?',
                'answers' => [
                    ['answer' => 'Trianoni békeszerződés*', 'is_correct' => true],
                    ['answer' => 'Versailles-i békeszerződés', 'is_correct' => false],
                    ['answer' => 'Saint-germaini békeszerződés', 'is_correct' => false],
                    ['answer' => 'Párizsi békeszerződés', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor írták alá a trianoni békét?',
                'answers' => [
                    ['answer' => '1920. június 4.*', 'is_correct' => true],
                    ['answer' => '1919. november 11.', 'is_correct' => false],
                    ['answer' => '1921. március 1.', 'is_correct' => false],
                    ['answer' => '1918. október 31.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt Magyarország kormányzója 1920–1944 között?',
                'answers' => [
                    ['answer' => 'Horthy Miklós*', 'is_correct' => true],
                    ['answer' => 'Bethlen István', 'is_correct' => false],
                    ['answer' => 'Gömbös Gyula', 'is_correct' => false],
                    ['answer' => 'Teleki Pál', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben tört ki a második világháború?',
                'answers' => [
                    ['answer' => '1939*', 'is_correct' => true],
                    ['answer' => '1938', 'is_correct' => false],
                    ['answer' => '1941', 'is_correct' => false],
                    ['answer' => '1940', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor szabadult fel Budapest a szovjet csapatok által a második világháborúban?',
                'answers' => [
                    ['answer' => '1945. február 13.*', 'is_correct' => true],
                    ['answer' => '1944. december 24.', 'is_correct' => false],
                    ['answer' => '1945. április 4.', 'is_correct' => false],
                    ['answer' => '1945. május 9.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt Magyarország kommunista pártjának főtitkára a Rákosi-korszakban?',
                'answers' => [
                    ['answer' => 'Rákosi Mátyás*', 'is_correct' => true],
                    ['answer' => 'Gerő Ernő', 'is_correct' => false],
                    ['answer' => 'Nagy Imre', 'is_correct' => false],
                    ['answer' => 'Kádár János', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor volt az 1956-os magyar forradalom?',
                'answers' => [
                    ['answer' => 'Október 23.*', 'is_correct' => true],
                    ['answer' => 'Október 15.', 'is_correct' => false],
                    ['answer' => 'November 4.', 'is_correct' => false],
                    ['answer' => 'Szeptember 30.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki volt Magyarország miniszterelnöke 1956 októberében?',
                'answers' => [
                    ['answer' => 'Nagy Imre*', 'is_correct' => true],
                    ['answer' => 'Kádár János', 'is_correct' => false],
                    ['answer' => 'Rákosi Mátyás', 'is_correct' => false],
                    ['answer' => 'Gerő Ernő', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben rendeztek szabad választásokat Magyarországon a rendszerváltás után először?',
                'answers' => [
                    ['answer' => '1990*', 'is_correct' => true],
                    ['answer' => '1989', 'is_correct' => false],
                    ['answer' => '1991', 'is_correct' => false],
                    ['answer' => '1988', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben csatlakozott Magyarország az Európai Unióhoz?',
                'answers' => [
                    ['answer' => '2004*', 'is_correct' => true],
                    ['answer' => '1999', 'is_correct' => false],
                    ['answer' => '2007', 'is_correct' => false],
                    ['answer' => '2002', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $qData) {
            DB::table('questions')->updateOrInsert(
                [
                    'subject_id' => $tortId,
                    'question'   => $qData['question'],
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $questionId = DB::table('questions')
                ->where('subject_id', $tortId)
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
