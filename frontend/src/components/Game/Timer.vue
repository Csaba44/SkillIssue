<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const RANKED_MAX_GUESS_TIME = import.meta.env.VITE_RANKED_MAX_GUESS_TIME ?? 30;
const SOLO_MAX_GUESS_TIME = import.meta.env.VITE_SOLO_MAX_GUESS_TIME ?? 3;

const props = defineProps({
  ranked: {
    type: Boolean,
    default: false,
    required: false,
  },
});

const secondsLeft = ref(props.ranked ? RANKED_MAX_GUESS_TIME : SOLO_MAX_GUESS_TIME);
let interval = null;

const emit = defineEmits(["countdown-end"]);

onMounted(() => {
  interval = setInterval(() => {
    if (secondsLeft.value <= 0) {
      secondsLeft.value = 0;
      clearInterval(interval);
      emit("countdown-end");
      return;
    }
    secondsLeft.value -= 1;
  }, 1000);
});

onUnmounted(() => {
  clearInterval(interval);
});
</script>

<template>
  <div class="flex gap-1 items-center text-2xl justify-center">
    <i class="fa-solid fa-clock text-error"></i>
    <p class="font-bold" :class="secondsLeft <= 5 && 'animate-pulse duration-75'">{{ secondsLeft }}s</p>
  </div>
</template>
