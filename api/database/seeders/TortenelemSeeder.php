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

            [
                'question' => 'Mi volt a királyi kancellária fő feladata?',
                'answers' => [
                    ['answer' => 'Oklevelek és levelek kiadása*', 'is_correct' => true],
                    ['answer' => 'Adók behajtása', 'is_correct' => false],
                    ['answer' => 'Hadsereg toborzása', 'is_correct' => false],
                    ['answer' => 'Bírósági ítéletek végrehajtása', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi volt a gróf feladata a Frank Királyságban?',
                'answers' => [
                    ['answer' => 'Adószedés, bíráskodás, katonai feladatok*', 'is_correct' => true],
                    ['answer' => 'Templomok építése és fenntartása', 'is_correct' => false],
                    ['answer' => 'Kereskedelem felügyelete', 'is_correct' => false],
                    ['answer' => 'Csak a király személyes védelme', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben adták ki a Magna Charta Libertatum-ot?',
                'answers' => [
                    ['answer' => '1215*', 'is_correct' => true],
                    ['answer' => '1066', 'is_correct' => false],
                    ['answer' => '1265', 'is_correct' => false],
                    ['answer' => '1189', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit biztosított a Magna Charta a főnemeseknek?',
                'answers' => [
                    ['answer' => 'Beleszólást a hatalomba és az adózásba*', 'is_correct' => true],
                    ['answer' => 'Teljes adómentességet', 'is_correct' => false],
                    ['answer' => 'Saját hadsereg tartásának jogát', 'is_correct' => false],
                    ['answer' => 'A király leváltásának jogát', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a hűbérbirtok?',
                'answers' => [
                    ['answer' => 'Feltételekkel adományozott örökletes birtok*', 'is_correct' => true],
                    ['answer' => 'Az egyház tulajdonában lévő föld', 'is_correct' => false],
                    ['answer' => 'A király személyes magánbirtoka', 'is_correct' => false],
                    ['answer' => 'Szabad parasztok által művelt közföld', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit nevezzük hűbérúrnak (senior)?',
                'answers' => [
                    ['answer' => 'Aki hűbérbirtokot adományoz szolgálatért*', 'is_correct' => true],
                    ['answer' => 'Aki hűbérbirtokot kap és hűséget esküszik', 'is_correct' => false],
                    ['answer' => 'Az egyházmegye feje', 'is_correct' => false],
                    ['answer' => 'A király személyes testőre', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mit nevezzük vazallusnak?',
                'answers' => [
                    ['answer' => 'Aki hűbérbirtokot kap és hűséget esküszik*', 'is_correct' => true],
                    ['answer' => 'Aki hűbérbirtokot adományoz', 'is_correct' => false],
                    ['answer' => 'Szabad földműves', 'is_correct' => false],
                    ['answer' => 'Városi polgár', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a rendi gyűlés fő feladata?',
                'answers' => [
                    ['answer' => 'Törvényhozás és adómegszavazás*', 'is_correct' => true],
                    ['answer' => 'A pápa megválasztása', 'is_correct' => false],
                    ['answer' => 'Hadüzenet kihirdetése', 'is_correct' => false],
                    ['answer' => 'Jobbágyok bírósági ítélkezése', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor jött létre a Pápai Állam?',
                'answers' => [
                    ['answer' => '751-ben*', 'is_correct' => true],
                    ['answer' => '800-ban', 'is_correct' => false],
                    ['answer' => '1054-ben', 'is_correct' => false],
                    ['answer' => '590-ben', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a feudalizmus lényege?',
                'answers' => [
                    ['answer' => 'Hűbérurak, vazallusok és jobbágyok függési rendszere*', 'is_correct' => true],
                    ['answer' => 'Városi polgárok önkormányzata', 'is_correct' => false],
                    ['answer' => 'Az egyház és az állam egysége', 'is_correct' => false],
                    ['answer' => 'Kereskedők céhrendszere', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a robot a feudalizmusban?',
                'answers' => [
                    ['answer' => 'A jobbágy kötelező ingyenmunkája a földesúrnak*', 'is_correct' => true],
                    ['answer' => 'Pénzben fizetett évi adó', 'is_correct' => false],
                    ['answer' => 'Az egyháznak fizetett terményadó', 'is_correct' => false],
                    ['answer' => 'Katonai szolgálat a királynak', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a terményjáradék?',
                'answers' => [
                    ['answer' => 'A jobbágy által terményben fizetett járadék*', 'is_correct' => true],
                    ['answer' => 'Az egyháznak fizetett pénzadó', 'is_correct' => false],
                    ['answer' => 'A király részére végzett fuvarszolgálat', 'is_correct' => false],
                    ['answer' => 'Kötelező katonai szolgálat', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi az ugar?',
                'answers' => [
                    ['answer' => 'Pihentetett, bevetetlen szántóföld*', 'is_correct' => true],
                    ['answer' => 'Tavasszal bevetett földterület', 'is_correct' => false],
                    ['answer' => 'A földesúr saját kezelésű birtoka', 'is_correct' => false],
                    ['answer' => 'Erdőből irtott új szántó', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Hány részre osztja a földet a háromnyomásos gazdálkodás?',
                'answers' => [
                    ['answer' => 'Háromra: őszi vetés, tavaszi vetés, ugar*', 'is_correct' => true],
                    ['answer' => 'Kettőre: vetés és ugar', 'is_correct' => false],
                    ['answer' => 'Négyre: négy évszak szerint', 'is_correct' => false],
                    ['answer' => 'Ötre: különböző gabonafajták szerint', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a tized?',
                'answers' => [
                    ['answer' => 'Az egyháznak fizetett adó, a termény tizede*', 'is_correct' => true],
                    ['answer' => 'A királynak fizetett hadiadó', 'is_correct' => false],
                    ['answer' => 'A jobbágy kötelező munkaszolgálata', 'is_correct' => false],
                    ['answer' => 'A városi polgárok vámfizetési kötelezettsége', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi az úriszék?',
                'answers' => [
                    ['answer' => 'A földesúr bírósága a jobbágyok felett*', 'is_correct' => true],
                    ['answer' => 'A nemesek gyűlése', 'is_correct' => false],
                    ['answer' => 'A király tanácsadó testülete', 'is_correct' => false],
                    ['answer' => 'Egyházi törvényszék', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a majorság?',
                'answers' => [
                    ['answer' => 'A földesúr saját kezelésű birtoka*', 'is_correct' => true],
                    ['answer' => 'A jobbágy által bérelt földterület', 'is_correct' => false],
                    ['answer' => 'Közösen használt legelő', 'is_correct' => false],
                    ['answer' => 'A király adományozta nemesi birtok', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Miben segített a szügyhám a középkorban?',
                'answers' => [
                    ['answer' => 'Megkönnyítette a ló igavonását*', 'is_correct' => true],
                    ['answer' => 'Védte a lovat csatában', 'is_correct' => false],
                    ['answer' => 'Gyorsabbá tette a lovak etetését', 'is_correct' => false],
                    ['answer' => 'Lehetővé tette a lovak patkolását', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor következett be a nagy egyházszakadás (szkizma)?',
                'answers' => [
                    ['answer' => '1054-ben*', 'is_correct' => true],
                    ['answer' => '800-ban', 'is_correct' => false],
                    ['answer' => '1215-ben', 'is_correct' => false],
                    ['answer' => '1378-ban', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mire osztódott szét a kereszténység 1054-ben?',
                'answers' => [
                    ['answer' => 'Nyugati (latin) és keleti (görög) egyházra*', 'is_correct' => true],
                    ['answer' => 'Katolikus és protestáns egyházra', 'is_correct' => false],
                    ['answer' => 'Római és bizánci birodalomra', 'is_correct' => false],
                    ['answer' => 'Pápai és császári egyházra', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a zsinat?',
                'answers' => [
                    ['answer' => 'Az egyház ügyeit megtárgyaló gyűlés*', 'is_correct' => true],
                    ['answer' => 'A pápa személyes imaterme', 'is_correct' => false],
                    ['answer' => 'Katonai gyűlés a keresztes háborúk előtt', 'is_correct' => false],
                    ['answer' => 'Szerzetesi közösség szabályzata', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki rendelkezik pápaválasztó joggal?',
                'answers' => [
                    ['answer' => 'A bíborosok*', 'is_correct' => true],
                    ['answer' => 'Az érsekek', 'is_correct' => false],
                    ['answer' => 'A püspökök', 'is_correct' => false],
                    ['answer' => 'A szerzetesrendek elöljárói', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a különbség érsek és püspök között?',
                'answers' => [
                    ['answer' => 'Az érsek egy egyháztartomány, a püspök egy egyházmegye vezetője*', 'is_correct' => true],
                    ['answer' => 'A püspök rangban felette áll az érseknek', 'is_correct' => false],
                    ['answer' => 'Az érsek csak kolostort vezet', 'is_correct' => false],
                    ['answer' => 'Nincs köztük rangbeli különbség', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Melyik évben alapították a bencés rendet?',
                'answers' => [
                    ['answer' => '529-ben*', 'is_correct' => true],
                    ['answer' => '313-ban', 'is_correct' => false],
                    ['answer' => '800-ban', 'is_correct' => false],
                    ['answer' => '1098-ban', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a bencés rend jelmondata?',
                'answers' => [
                    ['answer' => '"Ora et labora" (Imádkozz és dolgozz)*', 'is_correct' => true],
                    ['answer' => '"Pax et bonum" (Béke és jó)', 'is_correct' => false],
                    ['answer' => '"Deus lo vult" (Isten akarja)', 'is_correct' => false],
                    ['answer' => '"Omnia vincit amor" (A szeretet mindent legyőz)', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mikor jöttek létre a koldulórendek?',
                'answers' => [
                    ['answer' => 'A XIII. században*', 'is_correct' => true],
                    ['answer' => 'A VI. században', 'is_correct' => false],
                    ['answer' => 'A X. században', 'is_correct' => false],
                    ['answer' => 'A XV. században', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Hol éltek és tevékenykedtek a koldulórendek tagjai?',
                'answers' => [
                    ['answer' => 'Városokban*', 'is_correct' => true],
                    ['answer' => 'Elszigetelt hegyvidéki kolostorokban', 'is_correct' => false],
                    ['answer' => 'A királyi udvarban', 'is_correct' => false],
                    ['answer' => 'Kizárólag a Szentföldön', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ki az eretnek?',
                'answers' => [
                    ['answer' => 'Az egyház tanításával ellenkező nézeteket valló személy*', 'is_correct' => true],
                    ['answer' => 'Más vallású hódító katona', 'is_correct' => false],
                    ['answer' => 'Az egyházi tizedet nem fizető paraszt', 'is_correct' => false],
                    ['answer' => 'A pápával politikailag szemben álló király', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mire szolgált az inkvizíció?',
                'answers' => [
                    ['answer' => 'Eretnekek felkutatására és elítélésére*', 'is_correct' => true],
                    ['answer' => 'Keresztes háborúk szervezésére', 'is_correct' => false],
                    ['answer' => 'Szerzetesrendek ellenőrzésére', 'is_correct' => false],
                    ['answer' => 'Pápai adók behajtására', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi a regula a szerzetességben?',
                'answers' => [
                    ['answer' => 'A szerzetesrend életét szabályozó szabályzat*', 'is_correct' => true],
                    ['answer' => 'A kolostor napi imájának rendje', 'is_correct' => false],
                    ['answer' => 'Az apát megválasztásának módja', 'is_correct' => false],
                    ['answer' => 'A pápa által kiadott rendelet', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Mi az uradalom?',
                'answers' => [
                    ['answer' => 'A nagybirtok egésze: majorság, jobbágytelkek, közös területek*', 'is_correct' => true],
                    ['answer' => 'Kizárólag a földesúr lakóhelye', 'is_correct' => false],
                    ['answer' => 'A király által adományozott egyházi birtok', 'is_correct' => false],
                    ['answer' => 'Szabad parasztok falusi közössége', 'is_correct' => false],
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
