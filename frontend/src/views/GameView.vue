<script setup>
import { onBeforeMount, onMounted, ref, useTemplateRef } from "vue";
import RankedWidget from "../components/Game/RankedWidget.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import api from "../config/api";
import { toast } from "vue-sonner";
import { sleep } from "../utils/sleep";
import { useRoute } from "vue-router";

const route = useRoute();

const question = ref("Betöltés folyamatban...");
const subject = ref("Betöltés folyamatban...");
const currentRound = ref(0);
const answers = ref([]);
const hasEnded = ref(false);
const questionToken = ref(null);
const correctAnswerId = ref(null);

const getNextQuestion = async (selectedAnswerId) => {
  try {
    // Submit previous if there was a selected answer
    if (selectedAnswerId) {
      console.log("Kiválaszottt: ", selectedAnswerId);
      const res = await api.post(`/api/answers/verify/${selectedAnswerId}`, {
        question_token: questionToken.value,
        game_token: route.params.gameToken,
        user_guess_time_ms: 1000,
      });
      console.log(res);

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
    console.log(res);

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
  await getNextQuestion();
});
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden" :gameSelectionDisabled="true">
    <div class="w-full h-full flex items-center justify-center">
      <RankedWidget v-if="!hasEnded" @onGetNextQuestion="getNextQuestion" :correctAnswerId="correctAnswerId" :question="question" :answers="answers" :currRoundNumber="currentRound" :totalRounds="5" />
      <div v-else class="text-center text-3xl font-bold text-accentGreen">A játszma véget ért.</div>
    </div>
  </ProtectedPageContainer>
</template>
