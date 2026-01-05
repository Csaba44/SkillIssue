<script setup>
import { onBeforeMount, ref, useTemplateRef } from "vue";
import RankedWidget from "../components/Game/RankedWidget.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import api from "../config/api";
import { toast } from "vue-sonner";

import { useRoute } from "vue-router";

const route = useRoute();

const question = ref("Betöltés folyamatban...");
const subject = ref("Betöltés folyamatban...");
const answers = ref([]);

onBeforeMount(async () => {
  try {
    const res = await api.post("/api/questions/get-one", { game_token: route.params.gameToken });

    if (res.status !== 200) {
      return toast.error("Ismeretlen hiba történt a kérdés lekérése során.");
    }

    question.value = res.data.question.question;
    subject.value = res.data.question.subject.name;
    answers.value = res.data.question.answers;
  } catch (error) {
    console.log(error);
    if (!error.response) {
      return toast.error("Ellenőrizze az internetkapcsolatát!");
    }

    return toast.error("Ismeretlen hiba történt a kérdés lekérése során.");
  }
});
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden" :gameSelectionDisabled="true">
    <div class="w-full h-full flex items-center justify-center">
      <RankedWidget :question="question" :answers="answers" :currRoundNumber="1" :totalRounds="5" />
    </div>
  </ProtectedPageContainer>
</template>
