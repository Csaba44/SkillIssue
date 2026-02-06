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
    <!----<i
      class="fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
  -->
      <Navbar/>

    <nav class="z-5 text-textWhite text-lg flex justify-center items-center gap-5 font-medium text-nowrap">
      <button :disabled="isMatchmaking" @click="setSelected('Ranked')" :class="selectedGameMode == 'Ranked' && 'text-error!'"
        class="disabled:text-textDisabled cursor-pointer enabled:hover:text-warning enabled:hover:scale-105 transition-all">Ranked</button>
      <button :disabled="isMatchmaking" @click="setSelected('Solo')" :class="selectedGameMode == 'Solo' && 'text-error!'"
        class="disabled:text-textDisabled cursor-pointer flex gap-1 items-center justify-center enabled:hover:text-warning enabled:hover:scale-105 transition-all">Solo<span
          class="hidden sm:flex"> gyakorlás</span></button>
    </nav>

    <Widget class="mt-10">
      <h1 class="text-2xl font-bold">Hello, {{ user.name }} 👋 Jelenlegi online játékosok száma: <span
          class="text-accentGreen">1159 fő</span></h1>
    </Widget>

    <div class="w-full mt-10 grid grid-cols-1 grid-rows-[1fr_1fr_5fr] gap-0 lg:gap-25 items-center">
      <div class="flex justify-center text-sm sm:text-lg">
        <Button v-if="!isMatchmaking" @click="startMatchmaking()" :disabled="!selectedGameMode"
          class="text-wrap w- sm:w-min bg-success! text-black! h-min sm:text-nowrap"
          :title="selectedGameMode ? `Meccskeresés elkezdése [${selectedGameMode}]` : 'Kérlek válassz játékmódot!'" />
        <Button v-else @click="stopMatchmaking()" :disabled="selectedGameMode === 'Solo'"
          class="w-min h-min text-nowrap bg-error!" title="Meccskeresés leállítása" />
      </div>
      <div class="flex justify-center text-center text-textWhite text-2xl md:text-5xl items-center">
        <h1 v-if="isMatchmaking && selectedGameMode == 'Solo'">Indítás...</h1>
        <h1 v-if="isMatchmaking && selectedGameMode == 'Ranked'">Meccskeresés <span
            class="text-accentGreen">folyamatban</span> <i class="fa-solid fa-clock text-accentGreen"></i> 1:12</h1>
      </div>
      <div class="w-full h-min grid sm:grid-cols-2 md:grid-cols-4 items-stretch justify-center gap-4 mt-15">
        <div class="flex justify-center">
          <Widget title="Level" class="w-80 px-5 flex flex-col justify-between h-full">
            <h1 class="text-accentYellow text-6xl font-bold">{{ user.level.level }}</h1>
            <p class="mt-auto">
              A következő szinthez szükséges: <span class="text-accentYellow">{{ xpToNext }} XP</span>
            </p>
          </Widget>
        </div>
        <div class="flex justify-center">
          <Widget title="Elo" class="w-80 px-5 flex flex-col justify-between h-full">
            <h1 class="text-accentPurple text-6xl font-bold">{{ user.elo }}</h1>
            <p class="mt-auto">
              Jelenlegi rangod: <span class="text-accentPurple">{{ user.rank.name }}</span>
            </p>
          </Widget>
        </div>
        <div class="flex justify-center">
          <Widget title="Játszott meccsek" class="w-80 px-5 flex flex-col justify-between h-full">
            <h1 class="text-accentGreen text-6xl font-bold">{{ user.matches_played }}</h1>
            <p class="mt-auto">
              TOP <span class="text-accentGreen">{{ user.top_ranking }}% 🫡</span>
            </p>
          </Widget>
        </div>
        <div class="flex justify-center">
          <Widget title="Streak" class="w-80 px-5 flex flex-col justify-between h-full">
            <h1 class="text-primary text-6xl font-bold">{{ user.streak_count }} nap</h1>
            <p class="mt-auto">
              <span class="text-primary">{{ user.streak_count }} napja</span> konzisztensen gyakorolsz!
            </p>
          </Widget>
        </div>
      </div>
    </div>
  </ProtectedPageContainer>
</template>
