<script setup>
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import Timer from "./Timer.vue";
import AnswerButton from "./AnswerButton.vue";
import { computed, ref, watch } from "vue";
import Button from "../Generic/Button.vue";
import { toast } from "vue-sonner";
import { useGameStore } from "../../stores/GameStore";
import { determineOpponent } from "../../utils/determineOpponent";

const userStore = useUserStore();
const { isAuthenticated, user } = storeToRefs(userStore);

const gameStore = useGameStore();
const { isOpponentOnline, currentRound, currentSubject, currentQuestion, currentQuestionToken, currentAnswers, actualAnswers, selectedAnswer, timeExpired, submittedAnswer, rejoining } = storeToRefs(gameStore);

const countdownEnded = ref(false);
const isSubmitting = ref(false);
const showWaiting = ref(false);
let waitingTimeout = null;

const isDev = import.meta.env.DEV;

const TOTAL_ROUNDS = import.meta.env.VITE_MAX_ROUNDS ?? 5;

const subjectConfig = computed(() => {
  switch (currentSubject.value) {
    case "Történelem":
      return { color: "text-accentGreen", border: "border-accentGreen/40", bg: "bg-accentGreen/10", icon: "fa-solid fa-landmark" };
    case "Irodalom":
      return { color: "text-accentYellow", border: "border-accentYellow/40", bg: "bg-accentYellow/10", icon: "fa-solid fa-feather-pointed" };
    case "Magyar nyelv":
      return { color: "text-accentPurple", border: "border-accentPurple/40", bg: "bg-accentPurple/10", icon: "fa-solid fa-language" };
    default:
      return { color: "text-white", border: "border-white/20", bg: "bg-white/10", icon: "fa-solid fa-circle-question" };
  }
});

const isQuestionReportSubmitted = ref(false);
const isUserReportSubmitted = ref(false);

const setSubmitting = () => {
  isSubmitting.value = true;
  waitingTimeout = setTimeout(() => {
    showWaiting.value = true;
  }, 600);
};

const submitAnswer = (forced = false) => {
  if (isSubmitting.value) return;
  if (selectedAnswer.value === null && !forced) return toast.error("Nincs kiválasztott válasz.");

  setSubmitting();
  gameStore.submitAnswer(selectedAnswer.value);
};

watch(
  timeExpired,
  (expired) => {
    if (!expired) return;
    submitAnswer(true);
  },
  { immediate: true },
);

watch(
  [rejoining, submittedAnswer],
  ([isRejoining, submitted]) => {
    if (isRejoining && submitted !== null) {
      setSubmitting();
    }
  },
  { immediate: true },
);
const opponent = computed(() => determineOpponent(user.value, gameStore.match));

const correctAnswerId = computed(() => actualAnswers.value?.correctAnswerId ?? null);
const opponentAnswerId = computed(() => actualAnswers.value?.opponentAnswerId ?? null);

watch(currentQuestion, (newQuestion, prevQuestion) => {
  if (newQuestion != prevQuestion && prevQuestion !== null) {
    countdownEnded.value = false;
    isSubmitting.value = false;
    showWaiting.value = false;
    isQuestionReportSubmitted.value = false;
    clearTimeout(waitingTimeout);
  }
});

const onAnswerSelect = (id) => {
  if (!isAuthenticated.value) return;
  if (countdownEnded.value) return;
  if (isSubmitting.value) return;

  gameStore.selectedAnswer = id;
};

const createQuestionReportClicked = () => {
  if (isQuestionReportSubmitted.value) return toast.warning("Ezt a kérdést már bejelentetted. Köszönjük!");

  if (!currentQuestion.value || !currentQuestionToken.value) return;

  isQuestionReportSubmitted.value = true;
  toast.info("Hamarosan megnyitjuk új lapon a kitöltendő bejelentést. Köszönjük!");
  setTimeout(() => {
    window.open(`/report/question?questiontoken=${currentQuestionToken.value}&question=${currentQuestion.value}&answers=${encodeURIComponent(JSON.stringify(currentAnswers.value))}`, "_blank");
  }, 1000);
};

const createUserReportClicked = () => {
  if (isUserReportSubmitted.value) return toast.warning("Ezt a felhasználüt már bejelentetted. Köszönjük!");

  if (!currentQuestion.value || !currentQuestionToken.value) return;

  isUserReportSubmitted.value = true;
  toast.info("Hamarosan megnyitjuk új lapon a kitöltendő bejelentést. Köszönjük!");

  setTimeout(() => {
    window.open(`/report/user?rankedtoken=${gameStore.match.ranked_token}&opponent=${opponent.value.player.userName ?? "Ismeretlen ellenfél"}&reportround=${currentRound.value ?? TOTAL_ROUNDS}`, "_blank");
  }, 1000);
};
</script>

<template>
  <Widget v-if="isAuthenticated" class="relative w-[95%] md:w-[75%] lg:w-[55%] xl:w-[40%] backdrop-blur-2xl bg-white/5 border border-white/10 shadow-2xl shadow-black/40 rounded-3xl flex flex-col items-center md:px-10 py-12 gap-6 text-textWhite">
    <div class="w-full flex justify-between items-center text-sm text-white/60">
      <span class="font-bold text-error"> {{ currentRound }} / {{ TOTAL_ROUNDS }} kör </span>

      <div class="flex items-center gap-3">
        <div v-if="currentSubject" class="flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold" :class="[subjectConfig.color, subjectConfig.border, subjectConfig.bg]">
          <i :class="subjectConfig.icon"></i>
          <span>{{ currentSubject }}</span>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-3 text-lg font-semibold">
      <span class="text-white">{{ user.name }}</span>
      <i class="fa-solid fa-trophy text-accentYellow text-xl"></i>
      <span class="text-accentPurple">
        {{ opponent.player.userName ?? "Ismeretlen ellenfél" }}
        {{ !isOpponentOnline ? "(kilépett)" : "" }}
      </span>
    </div>

    <div class="flex gap-5 flex-col sm:flex-row">
      <button @click="createUserReportClicked" class="flex cursor-pointer items-center gap-1.5 text-xs text-white/30 hover:text-white/60 transition-colors duration-200">
        <i class="fa-solid fa-user-slash text-[11px]"></i>
        <span>Ellenfél bejelentése</span>
      </button>
      <button @click="createQuestionReportClicked" class="flex cursor-pointer items-center gap-1.5 text-xs text-white/30 hover:text-white/60 transition-colors duration-200">
        <i class="fa-solid fa-flag text-[11px]"></i>
        <span>Kérdés bejelentése</span>
      </button>
    </div>

    <div class="text-center space-y-4">
      <h1 class="text-2xl md:text-3xl font-bold leading-snug">{{ currentQuestion ?? "" }}</h1>
      <div class="flex justify-center">
        <Timer :ranked="true" :key="currentRound ?? 1" @countdown-end="() => console.log('Client side timer ended.')" />
      </div>
    </div>

    <div class="w-full flex flex-col gap-4 mt-4" :class="isSubmitting ? 'opacity-60 pointer-events-none' : ''">
      <template v-for="answer in currentAnswers" :key="answer.id">
        <AnswerButton @click="onAnswerSelect(answer.id)" :disabled="countdownEnded || isSubmitting" :isSelected="answer.id === selectedAnswer" :isCorrect="correctAnswerId !== null && answer.id === correctAnswerId" :isOpponentAnswer="opponentAnswerId !== null && answer.id === opponentAnswerId">
          {{ isDev ? answer.answer : answer.answer.replace("*", "") }}
        </AnswerButton>
      </template>
    </div>

    <div v-if="isSubmitting && correctAnswerId === null" class="mt-2 flex items-center gap-2 text-white/50 text-sm">
      <i class="fa-solid fa-check text-accentGreen text-base"></i>
      <span>Válasz elküldve</span>
    </div>

    <div v-if="showWaiting && correctAnswerId === null" class="flex flex-col items-center gap-3 text-white/80 text-center">
      <div class="flex items-center gap-3">
        <div class="flex gap-1">
          <span class="w-2 h-2 bg-accentYellow rounded-full animate-bounce"></span>
          <span class="w-2 h-2 bg-accentYellow rounded-full animate-bounce [animation-delay:0.2s]"></span>
          <span class="w-2 h-2 bg-accentYellow rounded-full animate-bounce [animation-delay:0.4s]"></span>
        </div>
        <span class="font-semibold text-sm md:text-base">
          <span class="text-accentPurple">{{ opponent.player.userName ?? "Az ellenfél" }}</span>
          még gondolkodik...
        </span>
      </div>
      <div class="text-xs text-white/40">A következő kérdés automatikusan indul.</div>
    </div>

    <Button title="Beküldés" class="mt-6 bg-linear-to-r from-accentGreen to-success text-black font-bold rounded-full px-10 py-3 shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300 disabled:opacity-40 disabled:scale-100 disabled:cursor-not-allowed disabled:shadow-none" :disabled="isSubmitting" @click="submitAnswer" />
  </Widget>
</template>
