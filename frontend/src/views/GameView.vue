<script setup>
import { onBeforeMount, onMounted, ref, useTemplateRef } from "vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import api from "../config/api";
import { toast } from "vue-sonner";
import { sleep } from "../utils/sleep";
import { useRoute } from "vue-router";
import SoloWidget from "../components/Game/SoloWidget.vue";
import { useGameStore } from "../stores/GameStore";
import RankedWidget from "../components/Game/RankedWidget.vue";
import RankedSummary from "../components/Game/RankedSummary.vue";
import { storeToRefs } from "pinia";

const route = useRoute();
const TOTAL_ROUNDS = import.meta.env.VITE_MAX_ROUNDS ?? 5;

const question = ref("Betöltés folyamatban...");
const subject = ref("Betöltés folyamatban...");
const currentRound = ref(0);
const answers = ref([]);
const hasEnded = ref(false);
const questionToken = ref(null);
const correctAnswerId = ref(null);

const gameStore = useGameStore();

const { matchResults } = storeToRefs(gameStore);

const getNextQuestion = async (selectedAnswerId) => {
  try {
    // Submit previous if there was a selected answer
    if (selectedAnswerId) {
      const res = await api.post(`/api/answers/verify/${selectedAnswerId}`, {
        question_token: questionToken.value,
        game_token: route.params.gameToken,
        user_guess_time_ms: 1000,
      });

      if (res.status !== 200) return;

      correctAnswerId.value = res.data.correct_answer_id;
      await sleep(1000);

      if (res.data.game_ended) {
        hasEnded.value = true;
        return toast.info("A játszma véget ért.");
      }
    }

    // Get next question
    const res = await api.post("/api/questions/get-one", { game_token: route.params.gameToken });

    if (res.status !== 200) {
      return toast.error("Ismeretlen hiba történt a kérdés lekérése során.");
    }

    question.value = res.data.question.question;
    subject.value = res.data.question.subject.name;
    answers.value = res.data.question.answers;
    currentRound.value = res.data.current_round;
    questionToken.value = res.data.question_token;
  } catch (error) {
    console.log(error);
    if (!error.response) {
      return toast.error("Ellenőrizze az internetkapcsolatát!");
    }

    if (error.response.status === 410) {
      hasEnded.value = true;
      return toast.info(error.response.data.error);
    }
    return toast.error("Ismeretlen hiba történt a kérdés lekérése során.");
  }
};

onBeforeMount(async () => {
  // If solo game
  if (route.params.gameToken) {
    await getNextQuestion();
  }
});
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden" :gameSelectionDisabled="true">
    <div class="w-full h-full flex items-center justify-center">
      <SoloWidget v-if="!hasEnded && route.params.gameToken" @onGetNextQuestion="getNextQuestion" :correctAnswerId="correctAnswerId" :question="question" :answers="answers" :currRoundNumber="currentRound" :totalRounds="TOTAL_ROUNDS" />
      <div v-if="hasEnded" class="text-center text-3xl font-bold text-accentGreen">A játszma véget ért.</div>

      <RankedWidget v-if="gameStore.match && route.params.matchUuid !== undefined" />
      <RankedSummary v-if="matchResults != null && !gameStore.match" />
    </div>
  </ProtectedPageContainer>
</template>
