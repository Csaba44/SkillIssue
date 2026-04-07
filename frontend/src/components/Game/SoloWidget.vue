<script setup>
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import Timer from "./Timer.vue";
import AnswerButton from "./AnswerButton.vue";
import { ref, watch, computed } from "vue";
import Button from "../Generic/Button.vue";
import { toast } from "vue-sonner";

const props = defineProps({
  question: String,
  subject: String,
  answers: Array,
  currRoundNumber: Number,
  totalRounds: String,
  correctAnswerId: Number,
  questionToken: String,
});

const emit = defineEmits(["onGetNextQuestion"]);

const userStore = useUserStore();
const { isAuthenticated, user } = storeToRefs(userStore);

const selectedAnswer = ref(null);
const countdownEnded = ref(false);
const isSubmitting = ref(false);

const isQuestionReportSubmitted = ref(false);

const subjectConfig = computed(() => {
  switch (props.subject) {
    case "Történelem":
      return {
        color: "text-accentGreen",
        border: "border-accentGreen/40",
        bg: "bg-accentGreen/10",
        icon: "fa-solid fa-landmark",
      };
    case "Irodalom":
      return {
        color: "text-accentYellow",
        border: "border-accentYellow/40",
        bg: "bg-accentYellow/10",
        icon: "fa-solid fa-feather-pointed",
      };
    case "Magyar nyelv":
      return {
        color: "text-accentPurple",
        border: "border-accentPurple/40",
        bg: "bg-accentPurple/10",
        icon: "fa-solid fa-language",
      };
    default:
      return { color: "text-white", border: "border-white/20", bg: "bg-white/10", icon: "fa-solid fa-circle-question" };
  }
});

watch(
  () => props.question,
  () => {
    selectedAnswer.value = null;
    countdownEnded.value = false;
    isSubmitting.value = false;
    isQuestionReportSubmitted.value = false;
  },
);

const onCountdownEnd = () => {
  if (!isAuthenticated.value) return;
  if (isSubmitting.value) return;

  countdownEnded.value = true;
  isSubmitting.value = true;
  emit("onGetNextQuestion", selectedAnswer.value);
};

const onAnswerSelect = (id) => {
  if (!isAuthenticated.value) return;
  if (countdownEnded.value) return;
  if (isSubmitting.value) return;

  selectedAnswer.value = id;
};

const getNext = () => {
  if (isSubmitting.value) return;

  if (selectedAnswer.value === null && !countdownEnded.value) return toast.error("Nincs kiválasztott válasz.");

  isSubmitting.value = true;
  emit("onGetNextQuestion", selectedAnswer.value);
};

const createQuestionReportClicked = () => {
  if (isQuestionReportSubmitted.value) return toast.warning("Ezt a kérdést már bejelentetted. Köszönjük!");
  if (!props.question || !props.questionToken) return;

  isQuestionReportSubmitted.value = true;
  toast.info("Hamarosan megnyitjuk új lapon a kitöltendő bejelentést. Köszönjük!");
  setTimeout(() => {
    window.open(
      `/report/question?questiontoken=${props.questionToken}&question=${props.question}&answers=${encodeURIComponent(JSON.stringify(props.answers))}`,
      "_blank",
    );
  }, 1000);
};
</script>

<template>
  <Widget
    v-if="isAuthenticated"
    class="relative w-[95%] md:w-[75%] lg:w-[55%] xl:w-[40%] backdrop-blur-2xl bg-white/5 border border-white/10 shadow-2xl shadow-black/40 rounded-3xl flex flex-col items-center md:px-10 py-12 gap-6 text-textWhite"
  >
    <div class="w-full flex justify-between items-center text-sm text-white/60">
      <span class="font-bold text-error"> {{ currRoundNumber }} / {{ totalRounds }} kör </span>

      <div class="flex items-center gap-3">
        <div
          v-if="subject"
          class="flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold"
          :class="[subjectConfig.color, subjectConfig.border, subjectConfig.bg]"
        >
          <i :class="subjectConfig.icon"></i>
          <span>{{ subject }}</span>
        </div>
      </div>
    </div>

    <div class="flex items-end">
      <button
        @click="createQuestionReportClicked"
        class="flex cursor-pointer items-center gap-1.5 text-xs text-white/30 hover:text-white/60 transition-colors duration-200"
      >
        <i class="fa-solid fa-flag text-[11px]"></i>
        <span>Kérdés bejelentése</span>
      </button>
    </div>

    <div class="flex items-center gap-3 text-lg font-semibold">
      <span class="text-white">{{ user.name }}</span>
    </div>

    <div class="text-center space-y-4">
      <h1 class="text-2xl md:text-3xl font-bold leading-snug">
        {{ props.question }}
      </h1>

      <div class="flex justify-center">
        <Timer :key="currRoundNumber" @countdown-end="onCountdownEnd" />
      </div>
    </div>

    <div class="w-full flex flex-col gap-4 mt-4" :class="isSubmitting ? 'opacity-60 pointer-events-none' : ''">
      <template v-for="answer in answers" :key="answer.id">
        <AnswerButton
          @click="onAnswerSelect(answer.id)"
          :disabled="countdownEnded || isSubmitting"
          :isSelected="answer.id === selectedAnswer"
          :isCorrect="correctAnswerId !== null && answer.id === correctAnswerId"
        >
          {{ answer.answer }}
        </AnswerButton>
      </template>
    </div>

    <div v-if="isSubmitting && correctAnswerId === null" class="mt-2 flex items-center gap-2 text-white/50 text-sm">
      <i class="fa-solid fa-check text-accentGreen text-base"></i>
      <span>Válasz elküldve</span>
    </div>

    <Button
      title="Következő"
      class="mt-6 bg-linear-to-r from-accentGreen to-success text-black font-bold rounded-full px-10 py-3 shadow-lg shadow-green-500/30 hover:scale-105 transition-all duration-300 disabled:opacity-40 disabled:scale-100 disabled:cursor-not-allowed disabled:shadow-none"
      :disabled="isSubmitting"
      @click="getNext"
    />
  </Widget>
</template>
