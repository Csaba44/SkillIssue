<script setup>
import { computed } from "vue";

const props = defineProps({
  disabled: Boolean,
  isSelected: Boolean,
  isCorrect: Boolean,
  isOpponentAnswer: Boolean,
});

const emit = defineEmits(["click"]);

const splitStyle = computed(() => {
  const mine = props.isSelected;
  const opp = props.isOpponentAnswer;
  const correct = props.isCorrect;

  const yellow = "#fbd22f";
  const purple = "#a06bf9";
  const green = "#02cb74";
  const white = "#fefefe";

  if (!mine && !opp && !correct) return { background: white };
  if (correct && !mine && !opp) return { background: green };
  if (mine && !opp && !correct) return { background: yellow };
  if (opp && !mine && !correct) return { background: purple };

  if (mine && correct && !opp) return { background: `linear-gradient(135deg, ${yellow} 50%, ${green} 50%)` };
  if (opp && correct && !mine) return { background: `linear-gradient(135deg, ${purple} 50%, ${green} 50%)` };
  if (mine && opp && !correct) return { background: `linear-gradient(135deg, ${yellow} 50%, ${purple} 50%)` };

  if (mine && opp && correct) return { background: `linear-gradient(135deg, ${yellow} 33.3%, ${purple} 33.3% 66.6%, ${green} 66.6%)` };

  return { background: white };
});
</script>

<template>
  <button @click="emit('click')" :disabled="props.disabled" class="w-full rounded-xl p-2 py-3 text-xl shadow-inner shadow-gray-500/80 transition-all duration-150 ease-in disabled:bg-textDisabled enabled:cursor-pointer text-black enabled:hover:animate-pulse relative overflow-hidden" :style="splitStyle">
    <slot></slot>
  </button>
</template>
