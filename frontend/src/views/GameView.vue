<script setup>
import RankedWidget from "../components/Game/RankedWidget.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";

const mockQuestion = "Melyik a legrégebbi magyar nyelvemlék?";

const mockAnswers = [
  {
    id: 1,
    question_id: 1,
    answer: "Leuveni krónika",
  },
  {
    id: 2,
    question_id: 1,
    answer: "Halotti beszéd",
  },
  {
    id: 3,
    question_id: 1,
    answer: "Kölcsey Himnusza",
  },
  {
    id: 4,
    question_id: 1,
    answer: "Jókai Mór novellái",
  },
];
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden" :gameSelectionDisabled="true">
    <div class="w-full h-full flex items-center justify-center">
      <RankedWidget :question="mockQuestion" :answers="mockAnswers" :currRoundNumber="1" :totalRounds="5" />
    </div>
  </ProtectedPageContainer>
</template>
