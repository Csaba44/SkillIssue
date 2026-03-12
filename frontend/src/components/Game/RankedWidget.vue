<script setup>
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import Timer from "./Timer.vue";
import AnswerButton from "./AnswerButton.vue";
import { computed, onMounted, ref, watch } from "vue";
import Button from "../Generic/Button.vue";
import { toast } from "vue-sonner";
import { useGameStore } from "../../stores/GameStore";
import { determineOpponent } from "../../utils/determineOpponent";

const userStore = useUserStore();
const { isAuthenticated, user } = storeToRefs(userStore);

const gameStore = useGameStore();
const { isOpponentOnline } = storeToRefs(gameStore);

const selectedAnswer = ref(null);
const countdownEnded = ref(false);
const isSubmitting = ref(false);

const TOTAL_ROUNDS = import.meta.env.VITE_MAX_ROUNDS ?? 5;

const opponent = computed(() => determineOpponent(user.value, gameStore.match));

/*watch(
  () => props.question,
  () => {
    selectedAnswer.value = null;
    countdownEnded.value = false;
    isSubmitting.value = false;
  },
);
*/

const onCountdownEnd = () => {
  if (!isAuthenticated.value) return;
  if (isSubmitting.value) return;

  countdownEnded.value = true;
  isSubmitting.value = true;

  // TODO: submit here & get next question
};

const onAnswerSelect = (id) => {
  if (!isAuthenticated.value) return;
  if (countdownEnded.value) return;

  selectedAnswer.value = id;
};

const getNext = () => {
  if (isSubmitting.value) return;

  if (selectedAnswer.value === null && !countdownEnded.value) return toast.error("Nincs kiválasztott válasz.");

  isSubmitting.value = true;
  // TOOD: submit here & get next question
};
</script>

<template>
  <Widget v-if="isAuthenticated" class="relative w-[95%] md:w-[75%] lg:w-[55%] xl:w-[40%] backdrop-blur-2xl bg-white/5 border border-white/10 shadow-2xl shadow-black/40 rounded-3xl flex flex-col items-center px-10 py-12 gap-6 text-textWhite">
    <div class="w-full flex justify-between items-center text-sm text-white/60">
      <span class="font-bold text-error"> {{ gameStore.currRoundNumber ?? 1 }} / {{ TOTAL_ROUNDS }} kör </span>
    </div>

    <div class="flex items-center gap-3 text-lg font-semibold">
      <span class="text-white">{{ user.name }}</span>
      <i class="fa-solid fa-swords text-accentYellow text-xl"></i>
      <span class="text-error">{{ opponent.player.userName ?? "Ismeretlen ellenfél" }} {{ !isOpponentOnline ? "(kilépett)" : "" }}</span>
    </div>

    <div class="text-center space-y-4">
      <h1 class="text-2xl md:text-3xl font-bold leading-snug">Question</h1>

      <div class="flex justify-center">
        <Timer :key="gameStore.currRoundNumber" @countdown-end="onCountdownEnd" />
      </div>
    </div>

    <!--<div class="w-full flex flex-col gap-4 mt-4">
      <template v-for="answer in answers" :key="answer.id">
        <AnswerButton @click="onAnswerSelect(answer.id)" :disabled="countdownEnded" :isSelected="answer.id === selectedAnswer" :isCorrect="correctAnswerId !== null && answer.id === correctAnswerId">
          {{ answer.answer }}
        </AnswerButton>
      </template>
    </div>-->

    <Button title="Következő" class="mt-6 bg-gradient-to-r from-accentGreen to-success text-black font-bold rounded-full px-10 py-3 shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300" :disabled="isSubmitting" @click="getNext" />
  </Widget>
</template>
