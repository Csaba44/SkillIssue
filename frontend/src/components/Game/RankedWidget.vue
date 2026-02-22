<script setup>
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import Timer from "./Timer.vue";
import AnswerButton from "./AnswerButton.vue";
import { ref, watch } from "vue";
import Button from "../Generic/Button.vue";
import { toast } from "vue-sonner";

const props = defineProps({
  question: String,
  answers: Array,
  currRoundNumber: Number,
  totalRounds: Number,
  correctAnswerId: Number,
});

const emit = defineEmits(["onGetNextQuestion"]);

const userStore = useUserStore();
const { isAuthenticated, user } = storeToRefs(userStore);

const selectedAnswer = ref(null);
const countdownEnded = ref(false);
const isSubmitting = ref(false);

watch(
  () => props.question,
  () => {
    selectedAnswer.value = null;
    countdownEnded.value = false;
    isSubmitting.value = false;
  },
);
const onCountdownEnd = () => {
  if (!isAuthenticated.value) return;
  if (isSubmitting.value) return;

  console.log("IDŐ LEJÁRT!");
  countdownEnded.value = true;
  isSubmitting.value = true;
  emit("onGetNextQuestion", selectedAnswer.value);
};

const onAnswerSelect = (id) => {
  if (!isAuthenticated.value) return;
  if (countdownEnded.value) return;

  selectedAnswer.value = id;
};

const getNext = () => {
  console.log(selectedAnswer);
  if (isSubmitting.value) return; 

  if (selectedAnswer.value === null && !countdownEnded.value) return toast.error("Nincs kiválasztott kérdés.");

  isSubmitting.value = true;
  emit("onGetNextQuestion", selectedAnswer.value);
};
</script>

<template>
  <Widget v-if="isAuthenticated" class="w-[90%] md:w-[75%] lg:w-[60%] xl:w-[40%] flex flex-col gap-3 p-8">
    <h2 class="font-bold text-2xl">
      <span class="text-textWhite">{{ user.name }}</span> <i class="fa-sharp fa-solid fa-swords text-accentYellow"></i> <span class="text-error">Ellenfél János</span>
    </h2>
    <h1 class="text-center mt-3 text-2xl">{{ props.question }}</h1>
    <Timer :key="currRoundNumber" @countdown-end="onCountdownEnd" />

    <div class="w-full px-10 flex flex-col gap-3">
      <template v-for="answer in answers">
        <AnswerButton @click="onAnswerSelect(answer.id)" :disabled="countdownEnded" :isSelected="answer.id === selectedAnswer" :isCorrect="correctAnswerId !== null && answer.id === correctAnswerId">
          {{ answer.answer }}
        </AnswerButton>
      </template>

      <div class="w-full flex items-center justify-center">
        <Button title="Következő" class="mt-3 bg-accentGreen! max-w-50!" @click="getNext" :disabled="isSubmitting" />
      </div>
    </div>

    <p class="text-xl text-center py-4 font-bold text-error">{{ currRoundNumber }} / {{ totalRounds }} kör</p>
  </Widget>
</template>
