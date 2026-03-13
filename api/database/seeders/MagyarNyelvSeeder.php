<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagyarNyelvSeeder extends Seeder
{
    public function run(): void
    {
        $subjectId = DB::table('subjects')
            ->where('name', 'Magyar nyelv')
            ->value('id');

        $questions = [
            [
                'question' => 'Mi a mondat fogalma a nyelvtanban?',
                'answers' => [
                    ['answer' => 'Legalább egy állítmányból álló, lezárt közlésegység*', 'is_correct' => true],
                    ['answer' => 'Két vagy több szóból álló szerkezet', 'is_correct' => false],
                    ['answer' => 'Csak főnévből és igéből álló egység', 'is_correct' => false],
                    ['answer' => 'Bekezdésnyi szövegrész', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik mondatrész a mondat gerince, amely nélkül nincs teljes mondat?',
                'answers' => [
                    ['answer' => 'Az állítmány*', 'is_correct' => true],
                    ['answer' => 'Az alany', 'is_correct' => false],
                    ['answer' => 'A tárgy', 'is_correct' => false],
                    ['answer' => 'A jelző', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a szófaja a "futás" szónak?',
                'answers' => [
                    ['answer' => 'Főnév*', 'is_correct' => true],
                    ['answer' => 'Ige', 'is_correct' => false],
                    ['answer' => 'Melléknév', 'is_correct' => false],
                    ['answer' => 'Határozószó', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik a helyes példa a minőségjelzőre?',
                'answers' => [
                    ['answer' => 'piros alma*', 'is_correct' => true],
                    ['answer' => 'Péter könyve', 'is_correct' => false],
                    ['answer' => 'három ház', 'is_correct' => false],
                    ['answer' => 'a ház teteje', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik példa mutat be összetett mondatot?',
                'answers' => [
                    ['answer' => 'Tudtam, hogy eljön.*', 'is_correct' => true],
                    ['answer' => 'A piros alma az asztalon van.', 'is_correct' => false],
                    ['answer' => 'Péter fut.', 'is_correct' => false],
                    ['answer' => 'Gyorsan és ügyesen dolgozott.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a különbség a mellérendelő és az alárendelő összetett mondat között?',
                'answers' => [
                    ['answer' => 'Mellérendelőben egyenrangú tagmondatok vannak, alárendelőben főmondat és mellékmondat*', 'is_correct' => true],
                    ['answer' => 'Mellérendelőben kötőszó nincs, alárendelőben mindig van', 'is_correct' => false],
                    ['answer' => 'Mellérendelőben több tagmondat van, alárendelőben mindig csak kettő', 'is_correct' => false],
                    ['answer' => 'Nincs köztük nyelvtani különbség', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik szóalakban van képző?',
                'answers' => [
                    ['answer' => 'futás*', 'is_correct' => true],
                    ['answer' => 'házban', 'is_correct' => false],
                    ['answer' => 'könyvek', 'is_correct' => false],
                    ['answer' => 'almát', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi az igekötő szerepe az igében?',
                'answers' => [
                    ['answer' => 'Módosítja az ige jelentését és aspektusát*', 'is_correct' => true],
                    ['answer' => 'Az ige idejét jelöli', 'is_correct' => false],
                    ['answer' => 'Az ige módját fejezi ki', 'is_correct' => false],
                    ['answer' => 'A személyt és számot jelöli', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik mondat tartalmaz alanyi ragozást?',
                'answers' => [
                    ['answer' => 'Látok egy madarat.*', 'is_correct' => true],
                    ['answer' => 'Látom a madarat.', 'is_correct' => false],
                    ['answer' => 'Látom azt.', 'is_correct' => false],
                    ['answer' => 'Nézem a filmet.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik szó tartalmaz toldalékot, amely egyszerre rag és jel?',
                'answers' => [
                    ['answer' => 'Egyik sem – a rag és a jel külön morfémák*', 'is_correct' => true],
                    ['answer' => 'házban', 'is_correct' => false],
                    ['answer' => 'futottam', 'is_correct' => false],
                    ['answer' => 'szépebb', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a szövegkohézió?',
                'answers' => [
                    ['answer' => 'A szöveg részeinek összefüggése, egységessége*', 'is_correct' => true],
                    ['answer' => 'A mondatok hosszúsága', 'is_correct' => false],
                    ['answer' => 'A bekezdések száma', 'is_correct' => false],
                    ['answer' => 'A szöveg stilisztikai értéke', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik stílusrétegbe tartozik egy tudományos cikk?',
                'answers' => [
                    ['answer' => 'Tudományos stílus*', 'is_correct' => true],
                    ['answer' => 'Szépirodalmi stílus', 'is_correct' => false],
                    ['answer' => 'Publicisztikai stílus', 'is_correct' => false],
                    ['answer' => 'Társalgási stílus', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a metafora?',
                'answers' => [
                    ['answer' => 'Névátvitel hasonlóságon alapuló képes kifejezés*', 'is_correct' => true],
                    ['answer' => 'Hangutánzó szó', 'is_correct' => false],
                    ['answer' => 'Ellentétes értelmű szavak párosítása', 'is_correct' => false],
                    ['answer' => 'Egy szó ismétlése nyomaték céljából', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik a helyes példa a megszemélyesítésre?',
                'answers' => [
                    ['answer' => 'A szél sír.*', 'is_correct' => true],
                    ['answer' => 'Fehér a hó.', 'is_correct' => false],
                    ['answer' => 'Gyors, mint a villám.', 'is_correct' => false],
                    ['answer' => 'Kék az ég.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a hasonlat?',
                'answers' => [
                    ['answer' => 'Két dolog közötti hasonlóság kifejezett összehasonlítása (mint, olyan… mint)*', 'is_correct' => true],
                    ['answer' => 'Egy szó ellentétének használata', 'is_correct' => false],
                    ['answer' => 'Elvont fogalom megszemélyesítése', 'is_correct' => false],
                    ['answer' => 'Hangok utánzása szavakkal', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi az alliteráció?',
                'answers' => [
                    ['answer' => 'Egymás melletti szavak azonos kezdőhangja*', 'is_correct' => true],
                    ['answer' => 'Sorvégi rím', 'is_correct' => false],
                    ['answer' => 'Szóismétlés', 'is_correct' => false],
                    ['answer' => 'Szótagok megismétlése', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik szófaj fejez ki cselekvést, történést vagy létezést?',
                'answers' => [
                    ['answer' => 'Ige*', 'is_correct' => true],
                    ['answer' => 'Főnév', 'is_correct' => false],
                    ['answer' => 'Melléknév', 'is_correct' => false],
                    ['answer' => 'Névmás', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a névelő szerepe a mondatban?',
                'answers' => [
                    ['answer' => 'A főnév határozott vagy határozatlan voltát jelöli*', 'is_correct' => true],
                    ['answer' => 'A melléknév fokát mutatja', 'is_correct' => false],
                    ['answer' => 'Az ige idejét jelöli', 'is_correct' => false],
                    ['answer' => 'A mondat alanyát emeli ki', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik mondatban szerepel határozói igenév?',
                'answers' => [
                    ['answer' => 'Énekelve ment haza.*', 'is_correct' => true],
                    ['answer' => 'Az éneklő lány szép.', 'is_correct' => false],
                    ['answer' => 'Az éneklés öröm.', 'is_correct' => false],
                    ['answer' => 'Szeret énekelni.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik a helyesírási alapelv, amely szerint "ház" szót h-á-z-nak írjuk?',
                'answers' => [
                    ['answer' => 'Kiejtés elve*', 'is_correct' => true],
                    ['answer' => 'Szóelemzés elve', 'is_correct' => false],
                    ['answer' => 'Hagyomány elve', 'is_correct' => false],
                    ['answer' => 'Egyszerűsítés elve', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik helyesírási alapelv alapján írjuk: "egészség" (nem "egésztség")?',
                'answers' => [
                    ['answer' => 'Kiejtés elve*', 'is_correct' => true],
                    ['answer' => 'Szóelemzés elve', 'is_correct' => false],
                    ['answer' => 'Hagyomány elve', 'is_correct' => false],
                    ['answer' => 'Egyszerűsítés elve', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a szintagma?',
                'answers' => [
                    ['answer' => 'Két vagy több szó grammatikai kapcsolata*', 'is_correct' => true],
                    ['answer' => 'Egy mondat legkisebb egysége', 'is_correct' => false],
                    ['answer' => 'Szövegrészek közötti kapcsolat', 'is_correct' => false],
                    ['answer' => 'Azonos szófajú szavak csoportja', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit nevezünk tőmondatnak?',
                'answers' => [
                    ['answer' => 'Csak alanyból és állítmányból álló mondatot*', 'is_correct' => true],
                    ['answer' => 'Egyetlen szóból álló mondatot', 'is_correct' => false],
                    ['answer' => 'Felszólító módú mondatot', 'is_correct' => false],
                    ['answer' => 'Hiányos mondatot', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi az eufémizmus?',
                'answers' => [
                    ['answer' => 'Szépítő kifejezés, kellemetlen dolgok enyhébb megnevezése*', 'is_correct' => true],
                    ['answer' => 'Túlzó kifejezésmód', 'is_correct' => false],
                    ['answer' => 'Ellentétes értelmű szavak egyidejű használata', 'is_correct' => false],
                    ['answer' => 'Idegen szavak magyarítása', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik példa szemlélteti a szóismétlést (epizeuxis)?',
                'answers' => [
                    ['answer' => 'Menj, menj, menj el innen!*', 'is_correct' => true],
                    ['answer' => 'Sír a szél.', 'is_correct' => false],
                    ['answer' => 'Fehér fehérség.', 'is_correct' => false],
                    ['answer' => 'Nagy a ház.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik mondat helyes a vesszőhasználat szempontjából?',
                'answers' => [
                    ['answer' => 'Tudtam, hogy eljön.*', 'is_correct' => true],
                    ['answer' => 'Tudtam hogy eljön.', 'is_correct' => false],
                    ['answer' => 'Tudtam, hogy, eljön.', 'is_correct' => false],
                    ['answer' => 'Tudtam hogy, eljön.', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit jelent a denotáció fogalma?',
                'answers' => [
                    ['answer' => 'A szó alapvető, szótári jelentése*', 'is_correct' => true],
                    ['answer' => 'A szó hangulati mellékjelentése', 'is_correct' => false],
                    ['answer' => 'A szó eredete', 'is_correct' => false],
                    ['answer' => 'A szó ellentéte', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a konnotáció?',
                'answers' => [
                    ['answer' => 'A szóhoz társuló hangulati, érzelmi mellékjelentés*', 'is_correct' => true],
                    ['answer' => 'A szó pontos szótári definíciója', 'is_correct' => false],
                    ['answer' => 'A szó nyelvtani neme', 'is_correct' => false],
                    ['answer' => 'A szó kiejtési szabálya', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik szófaj a "gyorsan" szó?',
                'answers' => [
                    ['answer' => 'Határozószó*', 'is_correct' => true],
                    ['answer' => 'Melléknév', 'is_correct' => false],
                    ['answer' => 'Ige', 'is_correct' => false],
                    ['answer' => 'Névutó', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik mondatban van alanyi mellékmondat?',
                'answers' => [
                    ['answer' => 'Az a fontos, hogy eljöjj.*', 'is_correct' => true],
                    ['answer' => 'Azt mondta, hogy eljön.', 'is_correct' => false],
                    ['answer' => 'Ott voltam, ahol te is.', 'is_correct' => false],
                    ['answer' => 'Azért jöttem, hogy segítsek.', 'is_correct' => false],
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
