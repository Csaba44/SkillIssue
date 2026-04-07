<script setup>
import { computed, onMounted, ref } from "vue";
import { useUserStore } from "../../stores/UserStore";
import api from "../../config/api";
import Loading from "../Generic/Loading.vue";

const props = defineProps({
  uuid: {
    type: String,
    required: true,
  },
});

const userStore = useUserStore();
const matchResults = ref(null);
const error = ref(null);

onMounted(async () => {
  try {
    const response = await api.get(`/api/game-matches/${props.uuid}`);
    matchResults.value = response.data;
  } catch (err) {
    error.value = err.response?.status ?? 500;
  }
});

const isSpectator = computed(() => {
  if (!matchResults.value) return true;
  const uid = userStore.user.id;
  return matchResults.value.playerA.userId !== uid && matchResults.value.playerB.userId !== uid;
});

const userMatchResult = computed(() => {
  if (!matchResults.value) return null;

  if (isSpectator.value) {
    return {
      isDraw: matchResults.value.isDraw,
      userStats: matchResults.value.playerA,
      opponentStats: matchResults.value.playerB,
    };
  }

  const userKey = matchResults.value.playerA.userId === userStore.user.id ? "playerA" : "playerB";

  return {
    isDraw: matchResults.value.isDraw,
    userStats: matchResults.value[userKey],
    opponentStats: matchResults.value[userKey === "playerA" ? "playerB" : "playerA"],
  };
});

const resultLabel = computed(() => {
  if (!userMatchResult.value) return null;

  if (userMatchResult.value.isDraw) {
    return {
      text: "Döntetlen",
      color: "text-accentYellow",
      border: "border-yellow-400/30",
      bg: "bg-yellow-400/10",
      icon: "fa-handshake",
    };
  }

  if (isSpectator.value) {
    const winner = matchResults.value.playerA.won
      ? matchResults.value.playerA.userName
      : matchResults.value.playerB.userName;
    return {
      text: `${winner} nyert`,
      color: "text-accentGreen",
      border: "border-green-400/30",
      bg: "bg-green-400/10",
      icon: "fa-trophy",
    };
  }

  if (userMatchResult.value.userStats.won) {
    return {
      text: "Győzelem!",
      color: "text-accentGreen",
      border: "border-green-400/30",
      bg: "bg-green-400/10",
      icon: "fa-trophy",
    };
  }

  return { text: "Vereség", color: "text-red-400", border: "border-red-400/30", bg: "bg-red-400/10", icon: "fa-skull" };
});
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center text-textWhite px-4 py-10">
    <div v-if="error" class="flex flex-col items-center gap-4 text-white/60">
      <i class="fa-solid fa-circle-exclamation text-4xl text-red-400"></i>
      <p v-if="error === 404" class="text-lg">Ez a meccs nem található.</p>
      <p v-else class="text-lg">Hiba történt a betöltés során.</p>
      <button
        @click="$router.push('/dashboard')"
        class="mt-4 px-8 py-3 text-base font-bold rounded-full bg-gradient-to-r from-accentGreen to-success text-black shadow-lg hover:scale-105 transition-all duration-300"
      >
        <i class="fa-solid fa-house mr-2"></i>Vissza a főoldalra
      </button>
    </div>

    <div v-else-if="userMatchResult && resultLabel" class="flex flex-col items-center w-full max-w-3xl">
      <div :class="[resultLabel.bg, resultLabel.border]" class="border px-8 py-3 rounded-full mb-6 backdrop-blur-lg">
        <span :class="resultLabel.color" class="text-2xl font-bold">
          <i :class="`fa-solid ${resultLabel.icon} mr-2`"></i>{{ resultLabel.text }}
        </span>
      </div>

      <h2 class="text-4xl font-bold mb-10">Meccs összesítő</h2>

      <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full mb-10">
        <div
          :class="
            isSpectator
              ? userMatchResult.isDraw
                ? ['border-yellow-400/30', 'bg-yellow-400/10']
                : userMatchResult.userStats.won
                  ? ['border-green-400/30', 'bg-green-400/10']
                  : ['border-red-400/30', 'bg-red-400/10']
              : [resultLabel.border, resultLabel.bg]
          "
          class="w-full sm:flex-1 p-6 rounded-2xl border backdrop-blur-xl text-center transition-all"
        >
          <p class="text-white/60 text-sm mb-1">{{ isSpectator ? "" : "Te" }}</p>
          <h3 class="text-xl font-bold mb-4">{{ userMatchResult.userStats.userName }}</h3>
          <p
            class="text-7xl font-bold"
            :class="
              isSpectator
                ? userMatchResult.isDraw
                  ? 'text-accentYellow'
                  : userMatchResult.userStats.won
                    ? 'text-accentGreen'
                    : 'text-red-400'
                : resultLabel.color
            "
          >
            {{ userMatchResult.userStats.score }}
          </p>
          <p
            class="text-sm mt-4"
            :class="userMatchResult.userStats.eloChange >= 0 ? 'text-accentGreen' : 'text-red-400'"
          >
            <i
              :class="`fa-solid ${userMatchResult.userStats.eloChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} mr-1`"
            ></i>
            {{ userMatchResult.userStats.eloChange >= 0 ? "+" : "" }}{{ userMatchResult.userStats.eloChange }} ELO
          </p>
        </div>

        <div class="text-3xl font-bold text-white/30 sm:block">VS</div>

        <div
          :class="
            isSpectator
              ? userMatchResult.isDraw
                ? ['border-yellow-400/30', 'bg-yellow-400/10']
                : userMatchResult.opponentStats.won
                  ? ['border-green-400/30', 'bg-green-400/10']
                  : ['border-red-400/30', 'bg-red-400/10']
              : ['border-white/10', 'bg-white/5']
          "
          class="w-full sm:flex-1 p-6 rounded-2xl border backdrop-blur-xl text-center transition-all"
        >
          <p class="text-white/60 text-sm mb-1">{{ isSpectator ? "" : "Ellenfél" }}</p>
          <h3 class="text-xl font-bold mb-4">{{ userMatchResult.opponentStats.userName }}</h3>
          <p
            class="text-7xl font-bold"
            :class="
              isSpectator
                ? userMatchResult.isDraw
                  ? 'text-accentYellow'
                  : userMatchResult.opponentStats.won
                    ? 'text-accentGreen'
                    : 'text-red-400'
                : 'text-white/80'
            "
          >
            {{ userMatchResult.opponentStats.score }}
          </p>
          <p
            class="text-sm mt-4"
            :class="userMatchResult.opponentStats.eloChange >= 0 ? 'text-accentGreen' : 'text-red-400'"
          >
            <i
              :class="`fa-solid ${userMatchResult.opponentStats.eloChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} mr-1`"
            ></i>
            {{ userMatchResult.opponentStats.eloChange >= 0 ? "+" : ""
            }}{{ userMatchResult.opponentStats.eloChange }} ELO
          </p>
        </div>
      </div>

      <div v-if="!isSpectator" class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full mb-10">
        <div
          class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-purple-400/20 hover:shadow-purple-400/20 hover:shadow-lg transition-all text-center"
        >
          <p class="text-white/60">ELO változás</p>
          <h3
            class="text-5xl font-bold mt-2"
            :class="userMatchResult.userStats.eloChange >= 0 ? 'text-accentGreen' : 'text-red-400'"
          >
            {{ userMatchResult.userStats.eloChange >= 0 ? "+" : "" }}{{ userMatchResult.userStats.eloChange }}
          </h3>
          <p class="text-sm mt-3 text-white/60">rangsorolt pont</p>
        </div>

        <div
          class="p-6 rounded-2xl bg-white/5 backdrop-blur-xl border border-yellow-400/20 hover:shadow-yellow-400/20 hover:shadow-lg transition-all text-center"
        >
          <p class="text-white/60">Pontszám</p>
          <h3 class="text-5xl text-accentYellow font-bold mt-2">{{ userMatchResult.userStats.score }}</h3>
          <p class="text-sm mt-3 text-white/60">helyes válasz</p>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-4">
        <button
          @click="$router.push('/dashboard')"
          class="px-10 py-4 text-lg font-bold rounded-full bg-gradient-to-r from-accentGreen to-success text-black shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300"
        >
          <i class="fa-solid fa-house mr-2"></i>Vissza a főoldalra
        </button>
      </div>
    </div>

    <Loading v-else message="Eredmények betöltése..." />
  </div>
</template>
