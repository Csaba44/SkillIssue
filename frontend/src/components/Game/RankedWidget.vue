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
const { isOpponentOnline, currentRound, currentSubject, currentQuestion, currentAnswers, actualAnswers } = storeToRefs(gameStore);

const selectedAnswer = ref(null);
const countdownEnded = ref(false);
const isSubmitting = ref(false);
const showWaiting = ref(false);
let waitingTimeout = null;

const TOTAL_ROUNDS = import.meta.env.VITE_MAX_ROUNDS ?? 5;

const opponent = computed(() => determineOpponent(user.value, gameStore.match));

const correctAnswerId = computed(() => actualAnswers.value?.correctAnswerId ?? null);
const opponentAnswerId = computed(() => actualAnswers.value?.opponentAnswerId ?? null);

watch(currentQuestion, (newQuestion, prevQuestion) => {
  if (newQuestion != prevQuestion) {
    selectedAnswer.value = null;
    countdownEnded.value = false;
    isSubmitting.value = false;
    showWaiting.value = false;
    clearTimeout(waitingTimeout);
  }
});

const setSubmitting = () => {
  isSubmitting.value = true;
  waitingTimeout = setTimeout(() => {
    showWaiting.value = true;
  }, 600);
};

const onCountdownEnd = () => {
  if (!isAuthenticated.value) return;
  if (isSubmitting.value) return;

  countdownEnded.value = true;
  setSubmitting();
};

const onAnswerSelect = (id) => {
  if (!isAuthenticated.value) return;
  if (countdownEnded.value) return;
  if (isSubmitting.value) return;

  selectedAnswer.value = id;
};

const submitAnswer = () => {
  if (isSubmitting.value) return;

  if (selectedAnswer.value === null && !countdownEnded.value) return toast.error("Nincs kiválasztott válasz.");

  setSubmitting();
  gameStore.submitAnswer(selectedAnswer.value);
};
</script>

<template>
  <Widget v-if="isAuthenticated" class="relative w-[95%] md:w-[75%] lg:w-[55%] xl:w-[40%] backdrop-blur-2xl bg-white/5 border border-white/10 shadow-2xl shadow-black/40 rounded-3xl flex flex-col items-center px-10 py-12 gap-6 text-textWhite">
    <div class="w-full flex justify-between items-center text-sm text-white/60">
      <span class="font-bold text-error">{{ currentRound ?? 1 }} / {{ TOTAL_ROUNDS }} kör</span>
    </div>

    <div class="flex items-center gap-3 text-lg font-semibold">
      <span class="text-white">{{ user.name }}</span>
      <i class="fa-solid fa-swords text-accentYellow text-xl"></i>
      <span class="text-accentPurple">
        {{ opponent.player.userName ?? "Ismeretlen ellenfél" }}
        {{ !isOpponentOnline ? "(kilépett)" : "" }}
      </span>
    </div>

    <div class="text-center space-y-4">
      <h1 class="text-2xl md:text-3xl font-bold leading-snug">{{ currentQuestion ?? "" }}</h1>
      <div class="flex justify-center">
        <Timer :key="currentRound ?? 1" @countdown-end="onCountdownEnd" />
      </div>
    </div>

    <div class="w-full flex flex-col gap-4 mt-4" :class="isSubmitting ? 'opacity-60 pointer-events-none' : ''">
      <template v-for="answer in currentAnswers" :key="answer.id">
        <AnswerButton @click="onAnswerSelect(answer.id)" :disabled="countdownEnded || isSubmitting" :isSelected="answer.id === selectedAnswer" :isCorrect="correctAnswerId !== null && answer.id === correctAnswerId" :isOpponentAnswer="opponentAnswerId !== null && answer.id === opponentAnswerId">
          {{ answer.answer }}
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

    <Button title="Beküldés" class="mt-6 bg-gradient-to-r from-accentGreen to-success text-black font-bold rounded-full px-10 py-3 shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300 disabled:opacity-40 disabled:scale-100 disabled:cursor-not-allowed disabled:shadow-none" :disabled="isSubmitting" @click="submitAnswer" />
  </Widget>
</template>
