<script setup>
import Navbar from "../components/HomePage/Navbar.vue";
import Container from "../components/Generic/Container.vue";
import Footer from "../components/HomePage/Footer.vue";

const lastUpdated = "2026. április 6.";

const sections = [
  {
    number: "1",
    title: "Bevezetés",
    icon: "fa-solid fa-shield-halved",
    content: 'A SkillIssue (a továbbiakban: „Szolgáltató") elkötelezett a felhasználók személyes adatainak védelme mellett. Ez a tájékoztató részletezi, hogy milyen adatokat gyűjtünk a rendszer működtetése során, és azokat hogyan használjuk fel.',
  },
  {
    number: "3",
    title: "Az adatkezelés célja és jogalapja",
    icon: "fa-solid fa-scale-balanced",
    content: "Az adatkezelés elsődleges célja a SkillIssue online játékplatform működtetése, a ranglista fenntartása és a közösségi élmény biztosítása. A jogalap a felhasználó önkéntes hozzájárulása a regisztrációval, valamint a szolgáltatás teljesítéséhez fűződő jogos érdek (pl. csalások megelőzése, kitiltások kezelése).",
  },
  {
    number: "4",
    title: "Sütik (Cookies) használata",
    icon: "fa-solid fa-cookie-bite",
    content: "A rendszer kizárólag a működéshez technikailag elengedhetetlen sütiket használ a Laravel Sanctum és munkamenet-kezelés révén. Marketing célú vagy harmadik féltől származó követő sütiket nem használunk.",
    bullets: [
      { label: "Session Cookie", desc: "A bejelentkezett állapot fenntartásához." },
      { label: "XSRF-TOKEN", desc: "A weboldal biztonságát szolgáló, CSRF elleni védelemhez." },
    ],
  },
  {
    number: "5",
    title: "Adattárolás és törlés",
    icon: "fa-solid fa-database",
    content: 'A felhasználói adatokat a fiók aktív fennállásáig tároljuk. A rendszer „Soft Delete" technológiát alkalmaz, ami azt jelenti, hogy törlés után az adatok nem jelennek meg a felületen, de adatbázis szinten archiválva maradnak a jogi és statisztikai következetesség megőrzése érdekében, kivéve, ha a felhasználó kifejezetten kéri az adatok végleges megsemmisítését.',
  },
  {
    number: "6",
    title: "Kapcsolat",
    icon: "fa-solid fa-envelope",
    content: "Adatvédelmi kérdésekkel vagy az adatok törlésével kapcsolatban keress minket az alkalmazás hivatalos elérhetőségein.",
  },
];

const dataGroups = [
  {
    title: "Felhasználói adatok",
    icon: "fa-solid fa-user",
    color: "text-accentPurple",
    bg: "bg-accentPurple/10",
    border: "border-accentPurple/20",
    items: [
      { label: "Azonosítók", desc: "Név, e-mail cím, titkosított jelszó." },
      { label: "Játékstatisztika", desc: "ELO pontszám, XP, elért szintek és rangok." },
      { label: "Eredmények", desc: "Megszerzett jelvények és azok elnyerésének ideje." },
    ],
  },
  {
    title: "Játékmenet és interakció",
    icon: "fa-solid fa-gamepad",
    color: "text-accentGreen",
    bg: "bg-accentGreen/10",
    border: "border-accentGreen/20",
    items: [
      { label: "Mérkőzések", desc: "Lejátszott meccsek adatai, ellenfelek, pontszámváltozások, eredmények." },
      { label: "Válaszok naplózása", desc: "Adott válaszok, helyes válaszok, körök száma és válaszadási idő." },
      { label: "Jelentések", desc: "Felhasználók vagy kérdések ellen tett panaszok." },
    ],
  },
  {
    title: "Biztonság és naplózás",
    icon: "fa-solid fa-lock",
    color: "text-accentYellow",
    bg: "bg-accentYellow/10",
    border: "border-accentYellow/20",
    items: [
      { label: "Munkamenetek", desc: "IP-cím, böngésző típusa, utolsó aktivitás ideje." },
      { label: "Bejelentkezési napló", desc: "IP-címek hash-elt formátumban a fiókbiztonság érdekében." },
      { label: "Kitiltások", desc: "Szabályszegés miatti korlátozások ténye, oka és lejárati dátuma." },
    ],
  },
];
</script>

<template>
  <Navbar />

  <div class="w-full min-h-[40vh] bg-bgDark flex flex-col items-center justify-center relative overflow-hidden pt-16">
    <i class="fa-solid fa-shield-halved text-accentPurple opacity-5 text-[600px] absolute pointer-events-none"></i>
    <div class="relative z-10 text-center px-4 py-20">
      <span class="text-xs uppercase tracking-widest font-bold text-accentPurple/70 mb-4 block">Jogi dokumentum</span>
      <h1 class="text-5xl md:text-7xl font-bold text-white mb-4">Adatkezelési <span class="text-accentPurple">Tájékoztató</span></h1>
      <p class="text-white/40 text-sm mt-4">Utolsó frissítés: {{ lastUpdated }}</p>
    </div>
  </div>

  <div class="bg-gray-100 py-16 px-4">
    <Container>
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-bgDark">2. A kezelt adatok köre</h2>
        <p class="text-gray-500 mt-2">Az adatbázisunkban az alábbi adatcsoportokat tároljuk a szolgáltatás biztosítása érdekében.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="group in dataGroups" :key="group.title" class="bg-white rounded-3xl shadow-md p-6 flex flex-col gap-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
          <div class="flex items-center gap-3 mb-2">
            <div :class="[group.bg, group.border, 'border', 'w-10', 'h-10', 'rounded-xl', 'flex', 'items-center', 'justify-center', 'shrink-0']">
              <i :class="[group.icon, group.color, 'text-lg']"></i>
            </div>
            <h3 class="font-bold text-bgDark text-lg">{{ group.title }}</h3>
          </div>
          <div v-for="item in group.items" :key="item.label" class="flex items-start gap-2">
            <i class="fa-solid fa-circle-check text-accentGreen mt-1 shrink-0"></i>
            <p class="text-gray-600 text-sm">
              <span class="font-semibold text-bgDark">{{ item.label }}:</span> {{ item.desc }}
            </p>
          </div>
        </div>
      </div>
    </Container>
  </div>

  <div class="bg-gray-100 w-full leading-none">
    <svg class="w-full h-auto block" viewBox="0 0 1920 100" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 0L80 12C160 24 320 48 480 48C640 48 800 24 960 16C1120 8 1280 16 1440 16C1600 16 1760 8 1840 4L1920 0V100H0V0Z" fill="#030F39" />
    </svg>
  </div>

  <div class="bg-bgDark py-16 px-4 -mt-1">
    <Container>
      <div class="max-w-3xl mx-auto flex flex-col gap-6">
        <div v-for="section in sections" :key="section.number" class="bg-white/5 border border-white/10 rounded-3xl p-6 flex gap-5 hover:bg-white/8 transition-all">
          <div class="w-10 h-10 rounded-xl bg-accentPurple/10 border border-accentPurple/20 flex items-center justify-center shrink-0 mt-0.5">
            <i :class="[section.icon, 'text-accentPurple', 'text-sm']"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-white font-bold text-lg mb-2">{{ section.number }}. {{ section.title }}</h3>
            <p class="text-white/60 text-sm leading-relaxed">{{ section.content }}</p>
            <ul v-if="section.bullets" class="mt-3 flex flex-col gap-2">
              <li v-for="bullet in section.bullets" :key="bullet.label" class="flex items-start gap-2 text-sm text-white/50">
                <i class="fa-solid fa-circle-check text-accentGreen mt-0.5 shrink-0"></i>
                <span
                  ><span class="font-semibold text-white/80">{{ bullet.label }}:</span> {{ bullet.desc }}</span
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </Container>
  </div>

  <Footer />
</template>
