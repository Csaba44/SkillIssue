<script setup>
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import Timer from "./Timer.vue";
import AnswerButton from "./AnswerButton.vue";
import { ref } from "vue";

const props = defineProps({
  question: String,
  answers: Array,
  currRoundNumber: Number,
  totalRounds: Number,
});

const userStore = useUserStore();
const { isAuthenticated, user } = storeToRefs(userStore);

const selectedAnswer = ref(null);
const countdownEnded = ref(false);

const onCountdownEnd = () => {
  if (!isAuthenticated.value) return;

  console.log("IDŐ LEJÁRT!");
  countdownEnded.value = true;
};

const onAnswerSelect = (id) => {
  if (!isAuthenticated.value) return;
  if (countdownEnded.value) return;

  selectedAnswer.value = id;
};
</script>

<template>
  <Widget v-if="isAuthenticated" class="w-[90%] md:w-[75%] lg:w-[60%] xl:w-[40%] flex flex-col gap-3 p-8">
    <h2 class="font-bold text-2xl">
      <span class="text-textWhite">{{ user.name }}</span> <i class="fa-sharp fa-solid fa-swords text-accentYellow"></i> <span class="text-error">Ellenfél János</span>
    </h2>
    <h1 class="text-center mt-3 text-2xl">{{ props.question }}</h1>
    <Timer @countdown-end="onCountdownEnd" />

    <div class="w-full px-10 flex flex-col gap-3">
      <template v-for="answer in answers">
        <AnswerButton @click="onAnswerSelect(answer.id)" :disabled="countdownEnded" :isSelected="answer.id === selectedAnswer">{{ answer.answer }}</AnswerButton>
      </template>
    </div>

    <p class="text-xl text-center py-4 font-bold text-error">{{ currRoundNumber }} / {{ totalRounds }} kör</p>
  </Widget>
</template>
