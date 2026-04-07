<script setup>
import { computed } from "vue";

const props = defineProps({
  disabled: Boolean,
  isSelected: Boolean,
  isCorrect: Boolean,
  isOpponentAnswer: Boolean,
});

const emit = defineEmits(["click"]);

const colors = {
  yellow: "var(--color-accentYellow)",
  purple: "var(--color-accentPurple)",
  green: "var(--color-accentGreen)",
  white: "var(--color-textWhite)",
};

const splitStyle = computed(() => {
  const { yellow, purple, green, white } = colors;
  const mine = props.isSelected;
  const opp = props.isOpponentAnswer;
  const correct = props.isCorrect;

  if (!mine && !opp && !correct) return { background: white };
  if (correct && !mine && !opp) return { background: green };
  if (mine && !opp && !correct) return { background: yellow };
  if (opp && !mine && !correct) return { background: purple };

  if (mine && correct && !opp) return { background: `linear-gradient(135deg, ${yellow} 50%, ${green} 50%)` };
  if (opp && correct && !mine) return { background: `linear-gradient(135deg, ${purple} 50%, ${green} 50%)` };
  if (mine && opp && !correct) return { background: `linear-gradient(135deg, ${yellow} 50%, ${purple} 50%)` };
  if (mine && opp && correct)
    return { background: `linear-gradient(135deg, ${yellow} 33.3%, ${purple} 33.3% 66.6%, ${green} 66.6%)` };

  return { background: white };
});

const isWin = computed(() => props.isSelected && props.isCorrect);
</script>

<template>
  <button
    @click="emit('click')"
    :disabled="props.disabled"
    :style="splitStyle"
    class="w-full rounded-xl p-2 py-3 text-xl shadow-inner shadow-gray-500/80 transition-all duration-150 ease-in disabled:bg-textDisabled enabled:cursor-pointer text-black enabled:hover:animate-pulse relative overflow-hidden"
    :class="isWin ? 'win-pulse' : ''"
  >
    <slot></slot>
  </button>
</template>

<style scoped>
.win-pulse {
  animation: winGlow 2s ease-out forwards;
}

@keyframes winGlow {
  0% {
    box-shadow: 0 0 0 0px var(--color-accentGreen);
    transform: scale(1);
  }
  30% {
    box-shadow: 0 0 0 6px color-mix(in srgb, var(--color-accentGreen) 50%, transparent);
    transform: scale(1.03);
  }
  60% {
    box-shadow: 0 0 0 10px color-mix(in srgb, var(--color-accentGreen) 20%, transparent);
    transform: scale(1.01);
  }
  100% {
    box-shadow: 0 0 0 12px transparent;
    transform: scale(1);
  }
}
</style>
