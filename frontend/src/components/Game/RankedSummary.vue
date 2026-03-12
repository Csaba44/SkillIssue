<script setup>
import { computed } from "vue";
import { useGameStore } from "../../stores/GameStore";
import { useUserStore } from "../../stores/UserStore";

const gameStore = useGameStore();
const userStore = useUserStore();

const userMatchResult = computed(() => {
  let userKey = null;
  if (gameStore.matchResults.playerA.userId === userStore.user.id) userKey = "playerA";
  else if (gameStore.matchResults.playerB.userId === userStore.user.id) userKey = "playerB";

  return {
    isDraw: gameStore.matchResults.isDraw,
    userStats: gameStore.matchResults[userKey],
    opponentStats: gameStore.matchResults[userKey == "playerA" ? "playerB" : "playerA"],
  };
});

const resultLabel = computed(() => {
  if (userMatchResult.value.isDraw) return { text: "Döntetlen", color: "text-accentYellow", border: "border-yellow-400/30", bg: "bg-yellow-400/10", icon: "fa-handshake" };
  if (userMatchResult.value.userStats.won) return { text: "Győzelem!", color: "text-accentGreen", border: "border-green-400/30", bg: "bg-green-400/10", icon: "fa-trophy" };
  return { text: "Vereség", color: "text-red-400", border: "border-red-400/30", bg: "bg-red-400/10", icon: "fa-skull" };
});
</script>

<template>
  <div class="relative overflow-hidden min-h-screen flex flex-col items-center justify-center text-textWhite px-4">
    <i class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>

    <div class="relative z-10 flex flex-col items-center w-full max-w-3xl">
      <div :class="[resultLabel.bg, resultLabel.border]" class="border px-8 py-3 rounded-full mb-6 backdrop-blur-lg">
        <span :class="resultLabel.color" class="text-2xl font-bold"> <i :class="`fa-solid ${resultLabel.icon} mr-2`"></i>{{ resultLabel.text }} </span>
      </div>

      <h2 class="text-4xl font-bold mb-10">Meccs összesítő</h2>

      <div class="flex items-center justify-center gap-6 w-full mb-10">
        <div :class="[resultLabel.border, resultLabel.bg]" class="flex-1 p-6 rounded-2xl border backdrop-blur-xl text-center transition-all">
          <p class="text-white/60 text-sm mb-1">Te</p>
          <h3 class="text-xl font-bold mb-4">{{ userMatchResult.userStats.userName }}</h3>
          <p class="text-7xl font-bold" :class="resultLabel.color">{{ userMatchResult.userStats.score }}</p>
          <p class="text-sm mt-4" :class="userMatchResult.userStats.eloChange >= 0 ? 'text-accentGreen' : 'text-red-400'">
            <i :class="`fa-solid ${userMatchResult.userStats.eloChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} mr-1`"></i>
            {{ userMatchResult.userStats.eloChange >= 0 ? "+" : "" }}{{ userMatchResult.userStats.eloChange }} ELO
          </p>
        </div>

        <div class="text-3xl font-bold text-white/30">VS</div>

        <div class="flex-1 p-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl text-center transition-all">
          <p class="text-white/60 text-sm mb-1">Ellenfél</p>
          <h3 class="text-xl font-bold mb-4">{{ userMatchResult.opponentStats.userName }}</h3>
          <p class="text-7xl font-bold text-white/80">{{ userMatchResult.opponentStats.score }}</p>
          <p class="text-sm mt-4" :class="userMatchResult.opponentStats.eloChange >= 0 ? 'text-accentGreen' : 'text-red-400'">
            <i :class="`fa-solid ${userMatchResult.opponentStats.eloChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} mr-1`"></i>
            {{ userMatchResult.opponentStats.eloChange >= 0 ? "+" : "" }}{{ userMatchResult.opponentStats.eloChange }} ELO
          </p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6 w-full mb-10">
        <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-purple-400/20 hover:shadow-purple-400/20 hover:shadow-lg transition-all text-center">
          <p class="text-white/60">ELO változás</p>
          <h3 class="text-5xl font-bold mt-2" :class="userMatchResult.userStats.eloChange >= 0 ? 'text-accentGreen' : 'text-red-400'">{{ userMatchResult.userStats.eloChange >= 0 ? "+" : "" }}{{ userMatchResult.userStats.eloChange }}</h3>
          <p class="text-sm mt-3 text-white/60">rangsorolt pont</p>
        </div>

        <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-yellow-400/20 hover:shadow-yellow-400/20 hover:shadow-lg transition-all text-center">
          <p class="text-white/60">Pontszám</p>
          <h3 class="text-5xl text-accentYellow font-bold mt-2">{{ userMatchResult.userStats.score }}</h3>
          <p class="text-sm mt-3 text-white/60">helyes válasz</p>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-4">
        <button @click="$router.push('/dashboard')" class="px-10 py-4 text-lg font-bold rounded-full bg-gradient-to-r from-accentGreen to-success text-black shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300"><i class="fa-solid fa-house mr-2"></i>Vissza a főoldalra</button>
      </div>
    </div>
  </div>
</template>
