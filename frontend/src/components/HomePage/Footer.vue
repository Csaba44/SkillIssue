<script setup>
import Container from "../Generic/Container.vue";
</script>

<template>
  <footer class="flex flex-col justify-end overflow-hidden relative pb-5 bg-bgFooter border-t border-white/10 pt-12 text-sm text-gray-400">
    <Container class="bg-bgFooter flex flex-col md:flex-row justify-start align-bottom gap-8">
      <div class="absolute top-5 text-gray-500">© 2026 SkillIssue.</div>

      <div class="flex flex-col gap-1">
        <p class="font-semibold text-white mb-2">Jogi dokumentumok</p>
        <router-link to="/privacy-policy" class="hover:text-white transition-colors duration-200"> Adatkezelési tájékoztató </router-link>
        <router-link to="/terms-of-service" class="hover:text-white transition-colors duration-200"> Általános Szerződési Feltételek (ÁSZF) </router-link>
      </div>

      <div>
        <p class="font-semibold text-white mb-2">Ügyfélszolgálat</p>
        <p>E-mail: <a href="mailto:support@skillissue.hu" class="hover:text-white">support@skillissue.hu</a></p>
        <p>Telefon: <a href="tel:+36307856045" class="hover:text-white">+36 30 785 6045</a></p>
      </div>

      <div class="absolute inset-0 flex items-end justify-end z-0 pointer-events-none">
        <span class="font-bold text-gray-300 opacity-30 text-4xl sm:text-6xl md:text-8xl xl:text-9xl"> <span class="text-[#F8F8F8]/20">Skill</span><span class="text-[#F8F8F8]/30">Issue.</span> </span>
      </div>
    </Container>
  </footer>
</template>
