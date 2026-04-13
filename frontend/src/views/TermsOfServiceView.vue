<script setup>
import Navbar from "../components/HomePage/Navbar.vue";
import Container from "../components/Generic/Container.vue";
import Footer from "../components/HomePage/Footer.vue";

const lastUpdated = "2026. április 6.";

const rules = ["Külső segédeszközök, botok vagy scriptek használata a válaszadáshoz.", "Többszörös regisztráció (multi-account) az ELO pontszám manipulálása érdekében.", "Más játékosok zaklatása a jelentési (report) rendszeren keresztül.", "Hibák (bugok) kihasználása előnyszerzés céljából."];

const sections = [
  {
    number: "1",
    title: "A szolgáltatás igénybevétele",
    icon: "fa-solid fa-right-to-bracket",
    content: "A SkillIssue egy kompetitív kvízplatform. A regisztrációval a felhasználó elismeri, hogy valós adatokkal regisztrál, és a szolgáltatást rendeltetésszerűen használja.",
  },
  {
    number: "3",
    title: "Szankciók és kitiltások",
    icon: "fa-solid fa-ban",
    content: "A Szolgáltató fenntartja a jogot, hogy a szabályszegő felhasználókat ideiglenesen vagy véglegesen korlátozza (Ban). A kitiltás mértékét és okát a moderátorok mérlegelik. Súlyos csalás esetén az ELO pontszám és az elért eredmények visszavonásra kerülhetnek.",
  },
  {
    number: "4",
    title: "Felelősség korlátozása",
    icon: "fa-solid fa-triangle-exclamation",
    content: "A Szolgáltató mindent megtesz a folyamatos üzemidőért, de nem vállal felelősséget a technikai hibákból, szerverleállásokból vagy internetkapcsolati problémákból eredő pontszámvesztésért. A béta állapotú funkciók hibáiért kártérítés nem követelhető.",
  },
  {
    number: "5",
    title: "Szellemi tulajdon",
    icon: "fa-solid fa-copyright",
    content: "A platformon található kérdések, grafikák és a forráskód a SkillIssue tulajdonát képezik. Ezek jogosulatlan másolása vagy kereskedelmi célú felhasználása jogi következményeket vonhat maga után.",
  },
  {
    number: "6",
    title: "Módosítások",
    icon: "fa-solid fa-pen-to-square cursor-pointer",
    content: "Fenntartjuk a jogot az ÁSZF és a játékszabályok (pl. ELO számítási logika) módosítására. A jelentős változásokról a felhasználókat az alkalmazáson belül tájékoztatjuk.",
  },
];
</script>

<template>
  <Navbar />

  <div class="w-full min-h-[40vh] bg-bgDark flex flex-col items-center justify-center relative overflow-hidden pt-16">
    <i class="fa-solid fa-file-contract text-accentGreen opacity-5 text-[600px] absolute pointer-events-none"></i>
    <div class="relative z-10 text-center px-4 py-20">
      <span class="text-xs uppercase tracking-widest font-bold text-accentGreen/70 mb-4 block">Jogi dokumentum</span>
      <h1 class="text-5xl md:text-7xl font-bold text-white mb-4">Általános Szerződési <span class="text-accentGreen">Feltételek</span></h1>
      <p class="text-white/40 text-sm mt-4">Utolsó frissítés: {{ lastUpdated }}</p>
    </div>
  </div>

  <div class="bg-gray-100 py-16 px-4">
    <Container>
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-bgDark">2. Felhasználói magatartás és Fair Play</h2>
        <p class="text-gray-500 mt-2">A SkillIssue közösségének tisztasága érdekében szigorúan tilos:</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <div v-for="(rule, index) in rules" :key="index" class="bg-white rounded-3xl shadow-md p-6 flex items-start gap-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
          <div class="w-10 h-10 rounded-xl bg-error/10 border border-error/20 flex items-center justify-center shrink-0 mt-0.5">
            <i class="fa-solid fa-xmark text-error text-sm"></i>
          </div>
          <p class="text-gray-600 text-sm leading-relaxed">{{ rule }}</p>
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
          <div class="w-10 h-10 rounded-xl bg-accentGreen/10 border border-accentGreen/20 flex items-center justify-center shrink-0 mt-0.5">
            <i :class="[section.icon, 'text-accentGreen', 'text-sm']"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-white font-bold text-lg mb-2">{{ section.number }}. {{ section.title }}</h3>
            <p class="text-white/60 text-sm leading-relaxed">{{ section.content }}</p>
          </div>
        </div>
      </div>
    </Container>
  </div>

  <Footer />
</template>
