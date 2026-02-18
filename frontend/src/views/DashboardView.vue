<script setup>
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import Widget from "../components/Generic/Widget.vue";
import { useUserStore } from "../stores/UserStore";
import { storeToRefs } from "pinia";
import Button from "../components/Generic/Button.vue";
import { computed, ref } from "vue";
import api from "../config/api";
import { toast } from "vue-sonner";
import router from "../config/router";


const userStore = useUserStore();
const { isAuthenticated, user } = storeToRefs(userStore);

const selectedGameMode = ref(false);
const isMatchmaking = ref(false);

const startMatchmaking = async () => {
  console.log(isAuthenticated.value);

  if (!isAuthenticated.value) return;
  if (isMatchmaking.value || !selectedGameMode.value) return;

  if (selectedGameMode.value == "Ranked") {
    // Handle matchmaking logic
  } else {
    try {
      const response = await api.post("/api/practice-sessions");

      if (!response.data.success || !response.data.game_token) {
        return toast.error("Ismeretlen hiba történt a játék létrehozása közben!");
      }

      router.push("/game/solo/" + response.data.game_token);
    } catch (error) {
      console.log(error);
      if (!error.response) {
        return toast.error("Ellenőrizd az internetkapcsolatod!");
      }

      return toast.error("Ismeretlen hiba történt a játék létrehozása közben!");
    }
  }

  isMatchmaking.value = true;
};

const stopMatchmaking = () => {
  if (!isMatchmaking) return;
  if (selectedGameMode.value === "Solo") return;
  isMatchmaking.value = false;
};

const setSelected = (mode) => {

  if (isMatchmaking.value) return;
  if (selectedGameMode.value === mode) return;
  selectedGameMode.value = mode;
};

const xpToNext = computed(() => user.value.next_level.min_xp - user.value.xp);
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i
      class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
    <Navbar />

    <section class="mt-20 flex flex-col items-center text-center text-textWhite">
      <h2 class="text-4xl font-bold mb-10">
        Válassz játékmódot
      </h2>
      <div class="flex flex-col sm:flex-row gap-8">

        <div @click="setSelected('Ranked')" :class="selectedGameMode === 'Ranked'
          ? 'border-primary bg-primary/10 scale-105'
          : 'border-white/10 bg-white/5'" class="cursor-pointer w-60 p-6 rounded-2xl border 
             backdrop-blur-lg transition-all duration-300 hover:scale-105">
          <i class="fa-solid fa-trophy text-3xl text-primary mb-4"></i>
          <h3 class="text-xl font-semibold">Ranked</h3>
          <p class="text-sm text-white/60 mt-2">
            Kompetitív rangsorolt meccs
          </p>
        </div>

        <div @click="setSelected('Solo')" :class="selectedGameMode === 'Solo'
          ? 'border-accentGreen bg-accentGreen/10 scale-105'
          : 'border-white/10 bg-white/5'" class="cursor-pointer w-60 p-6 rounded-2xl border 
             backdrop-blur-lg transition-all duration-300 hover:scale-105">
          <i class="fa-solid fa-bullseye text-3xl text-accentGreen mb-4"></i>
          <h3 class="text-xl font-semibold">Solo</h3>
          <p class="text-sm text-white/60 mt-2">
            Egyjátékos gyakorlás
          </p>
        </div>

      </div>

      <div class="mt-12 flex flex-col items-center gap-6">

        <button v-if="!isMatchmaking" @click="startMatchmaking" :disabled="!selectedGameMode" class="px-14 py-5 text-xl font-bold rounded-full
           bg-gradient-to-r from-accentGreen to-success
           text-black
           shadow-lg shadow-green-500/30
           hover:scale-105 transition-all duration-300
           disabled:opacity-40 disabled:cursor-not-allowed">
          {{ selectedGameMode
            ? `Meccskeresés elkezdése [${selectedGameMode}]`
            : 'Kérlek válassz játékmódot!' }}
        </button>

        <button v-else @click="stopMatchmaking" :disabled="selectedGameMode === 'Solo'" class="px-14 py-5 text-xl font-bold rounded-full
           bg-gradient-to-r from-red-500 to-error
           text-white
           shadow-lg shadow-red-500/30
           hover:scale-105 transition-all duration-300
           disabled:opacity-40 disabled:cursor-not-allowed">
          Meccskeresés leállítása
        </button>

        <div class="text-center text-2xl md:text-4xl font-semibold text-white min-h-[40px]">

          <span v-if="isMatchmaking && selectedGameMode === 'Solo'">
            Indítás...
          </span>

          <span v-if="isMatchmaking && selectedGameMode === 'Ranked'">
            Meccskeresés
            <span class="text-accentGreen">folyamatban</span>
            <i class="fa-solid fa-clock text-accentGreen ml-2"></i>
            1:12
          </span>

        </div>

      </div>


      <div class="mt-3 text-accentGreen text-sm bg-accentGreen/10 px-4 py-2 rounded-full">
        ● 1159 játékos online
      </div>

    </section>


    <section class="mt-24 grid sm:grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">

      <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-yellow-400/20 
              hover:shadow-yellow-400/20 hover:shadow-lg transition-all">
        <p class="text-white/60">Level</p>
        <h3 class="text-5xl text-accentYellow font-bold mt-2">
          {{ user.level.level }}
        </h3>
        <p class="text-sm mt-3 text-white/60">
          {{ xpToNext }} XP a következő szintig
        </p>
      </div>

      <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-purple-400/20 
              hover:shadow-purple-400/20 hover:shadow-lg transition-all">
        <p class="text-white/60">Elo</p>
        <h3 class="text-5xl text-accentPurple font-bold mt-2">
          {{ user.elo }}
        </h3>
        <p class="text-sm mt-3 text-white/60">
          {{ user.rank.name }}
        </p>
      </div>

      <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-green-400/20 
              hover:shadow-green-400/20 hover:shadow-lg transition-all">
        <p class="text-white/60">Meccsek</p>
        <h3 class="text-5xl text-accentGreen font-bold mt-2">
          {{ user.matches_played }}
        </h3>
        <p class="text-sm mt-3 text-white/60">
          TOP {{ user.top_ranking }}%
        </p>
      </div>

      <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-orange-400/20 
              hover:shadow-orange-400/20 hover:shadow-lg transition-all">
        <p class="text-white/60">Streak</p>
        <h3 class="text-5xl text-orange-400 font-bold mt-2">
          {{ user.streak_count }} nap
        </h3>
        <p class="text-sm mt-3 text-white/60">
          Így tovább! 🔥
        </p>
      </div>

    </section>

  </ProtectedPageContainer>
</template>
