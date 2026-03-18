<script setup>
import router from "../config/router";
import Navbar from "../components/HomePage/Navbar.vue";
import Header from "../components/HomePage/Header.vue";
import Container from "../components/Generic/Container.vue";
import AboutCard from "../components/HomePage/AboutCard.vue";
import PricingCard from "../components/HomePage/PricingCard.vue";
</script>

<template>
  <Navbar></Navbar>
  <Header></Header>

  <div class="relative h-full bg-gray-100 xl:pb-20 pb-12">
    <div class="z-0 top-0 left-0 block leading-none w-screen">
      <svg class="w-screen h-auto block" width="1920" height="242" viewBox="0 0 1920 242" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 242L80 213.675C160 185.047 320 128.853 480 128.625C640 128.853 800 185.047 960 205.55C1120 226.053 1280 209.347 1440 209.575C1600 209.347 1760 226.053 1840 233.875L1920 242V-1H1840C1760 -1 1600 -1 1440 -1C1280 -1 1120 -1 960 -1C800 -1 640 -1 480 -1C320 -1 160 -1 80 -1H0V242Z" fill="#030F39" />
      </svg>
      <h1 class="text-6xl pb-5 font-bold text-bgDark text-nowrap text-right mr-13">Rólunk</h1>
    </div>

    <Container class="w-full h-min relative flex items-center justify-center z-10">
      <div class="grid gap-6 grid-cols-1 md:grid-cols-2 sm:grid-cols-2 xl:grid-cols-4">
        <AboutCard title="Célunk" desc="Újragondolni a tanulást úgy, hogy izgalmas, játékos és motiváló élmény legyen." :icon="'fa-solid fa-bullseye'"></AboutCard>
        <AboutCard icon="fa-solid fa-users" title="Kik vagyunk" desc=" Egy lelkes fejlesztői és kreatív csapat, akik hisznek a modern, élvezetes tanulásban." :icon="'fa-regular fa-bullseye-arrow'" class="md:mt-50" :type="'green'"></AboutCard>
        <AboutCard icon="fa-solid fa-dumbbell" title="Erősségeink" desc="Pontokkal, streakekkel és kihívásokkal tesszük élvezetesebbé a tanulást." class="md:bottom-30 xl:bottom-0" :type="'orange'"></AboutCard>
        <AboutCard icon="fa-solid fa-hourglass-half" title="Jövőbeli tervek" desc="A legélvezetesebb magyar tanulóplatformot építjük, ahol minden diák hatékonyan tud tanulni." class="md:mt-50 md:bottom-30 xl:bottom-0" :type="'yellow'"></AboutCard>
      </div>
    </Container>
    <div class="absolute inset-0 flex items-end justify-start z-0 pointer-events-none">
      <span class="font-bold text-gray-400 opacity-30 text-4xl sm:text-6xl md:text-8xl xl:text-9xl"> SkillIssue. </span>
    </div>
  </div>

  <section class="pt-20 h-full bg-bgDark text-white pb-16 relative overflow-hidden">
    <Container>
      <h2 class="lg:text-5xl text-3xl md:text-4xl text-nowrap text-right font-bold tracking-wide mb-12">SZOLGÁLTATÁSAINK</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto justify-items-center">
        <PricingCard subscription="Alapcsomag" price="0" :features="['Középszintű érettséghez', 'Korlátlan gyakorlás', 'Pontok', 'XP rendszer', 'Napi streakek']" />

        <PricingCard :buttonDisabled="true" buttonTitle="Hamarosan..." subscription="Pro csomag" price="---" :features="['Emelt szintű érettséghez', 'Minden az alapcsomagból', 'Részletes statisztikák', 'Co-op mód', 'Tanári konzultáció']" />
      </div>
    </Container>
  </section>

  <footer class="flex flex-col justify-end overflow-hidden relative pb-5 bg-bgFooter border-t border-white/10 pt-12 text-sm text-gray-400">
    <Container class="bg-bgFooter flex flex-col md:flex-row justify-start align-bottom gap-8">
      <div class="absolute top-5 text-gray-500">© 2026 SkillIssue.</div>

      <div>
        <p class="font-semibold text-white mb-2">Jogi dokumentumok</p>
        <p>Adatkezelési tájékoztató</p>
        <p>Általános Szerződési Feltételek (ÁSZF)</p>
      </div>

      <div>
        <p class="font-semibold text-white mb-2">Ügyfélszolgálat</p>
        <p>E-mail: support@skillissue.hu</p>
        <p>Telefon: +36 30 785 6045</p>
      </div>

      <div class="absolute inset-0 flex items-end justify-end z-0 pointer-events-none">
        <span class="font-bold text-gray-300 opacity-30 text-4xl sm:text-6xl md:text-8xl xl:text-9xl"> <span class="text-[#F8F8F8]/20">Skill</span><span class="text-[#F8F8F8]/30">Issue.</span></span>
      </div>
    </Container>
  </footer>
</template>
