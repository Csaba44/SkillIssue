<script setup>
import { computed, onMounted, ref } from "vue";
import api from "../../config/api";
import Loading from "../Generic/Loading.vue";

const props = defineProps({
  id: {
    type: Number,
    required: true,
  },
});

const session = ref(null);
const error = ref(null);

onMounted(async () => {
  try {
    const response = await api.get(`/api/practice-sessions/${props.id}`);
    session.value = response.data;
  } catch (err) {
    error.value = err.response?.status ?? 500;
  }
});

const accuracy = computed(() => {
  if (!session.value) return 0;
  return Math.round((session.value.score / session.value.maxRounds) * 100);
});

const wrongAnswers = computed(() => {
  if (!session.value) return 0;
  return session.value.maxRounds - session.value.score;
});

const formattedDate = computed(() => {
  if (!session.value) return "";
  return new Date(session.value.createdAt).toLocaleString("hu-HU", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
});
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center text-textWhite px-4 py-10">
    <div v-if="error" class="flex flex-col items-center gap-4 text-white/60">
      <i class="fa-solid fa-circle-exclamation text-4xl text-red-400"></i>
      <p v-if="error === 404" class="text-lg">Ez a játszma nem található.</p>
      <p v-else class="text-lg">Hiba történt a betöltés során.</p>
      <button @click="$router.push('/dashboard')" class="mt-4 px-8 py-3 text-base font-bold rounded-full bg-gradient-to-r from-accentGreen to-success text-black shadow-lg hover:scale-105 transition-all duration-300"><i class="fa-solid fa-house mr-2"></i>Vissza a főoldalra</button>
    </div>

    <div v-else-if="session" class="flex flex-col items-center w-full max-w-3xl">
      <div class="border border-purple-400/30 bg-purple-400/10 px-8 py-3 rounded-full mb-6 backdrop-blur-lg">
        <span class="text-accentYellow text-2xl font-bold">
          <i class="fa-solid fa-brain mr-2"></i>
          Solo gyakorlás
        </span>
      </div>

      <h2 class="text-4xl font-bold mb-10">Gyakorlás összesítő</h2>

      <div class="p-8 rounded-2xl border border-yellow-400/20 bg-yellow-400/10 backdrop-blur-xl text-center w-full mb-8">
        <p class="text-white/60 mb-2">Pontszám</p>
        <h3 class="text-7xl font-bold text-accentYellow">{{ session.score }} / {{ session.maxRounds }}</h3>
        <p class="text-white/60 mt-2">{{ accuracy }}% helyes válasz</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 w-full mb-10">
        <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-green-400/20 text-center">
          <p class="text-white/60">Helyes válasz</p>
          <h3 class="text-4xl font-bold text-accentGreen mt-2">{{ session.score }}</h3>
        </div>

        <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-red-400/20 text-center">
          <p class="text-white/60">Helytelen</p>
          <h3 class="text-4xl font-bold text-red-400 mt-2">{{ wrongAnswers }}</h3>
        </div>

        <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-purple-400/20 text-center">
          <p class="text-white/60">Pontosság</p>
          <h3 class="text-4xl font-bold text-accentYellow mt-2">{{ accuracy }}%</h3>
        </div>
      </div>

      <div class="p-8 rounded-2xl border border-purple-400/20 bg-white/5 backdrop-blur-xl text-center w-full mb-8">
        <p class="text-white/60 mb-2">XP változás</p>
        <h3 class="text-6xl font-bold" :class="session.xpChange >= 0 ? 'text-accentGreen' : 'text-red-400'">{{ session.xpChange >= 0 ? "+" : "" }}{{ session.xpChange }}</h3>
        <p class="text-sm mt-2 text-white/60">{{ session.xpBefore }} → {{ session.xpAfter }}</p>
      </div>

      <div class="p-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl text-center w-full mb-10">
        <p class="text-white/60">Lejátszva</p>
        <h3 class="text-xl font-bold mt-2">{{ formattedDate }}</h3>
      </div>

      <button @click="$router.push('/dashboard')" class="px-10 py-4 text-lg font-bold rounded-full bg-gradient-to-r from-accentGreen to-success text-black shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300">
        <i class="fa-solid fa-house mr-2"></i>
        Vissza a főoldalra
      </button>
    </div>
    <Loading v-else message="Eredmények betöltése..." />
  </div>
</template>
