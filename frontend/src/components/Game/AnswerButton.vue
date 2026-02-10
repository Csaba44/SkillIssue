<script setup>
const props = defineProps({
  disabled: Boolean,
  isSelected: Boolean,
  isCorrect: Boolean,
});
const emit = defineEmits(["click"]);
</script>

<template>
  <button
    @click="emit('click')"
    :disabled="props.disabled"
    class="w-full rounded-xl p-2 py-3 text-xl shadow-inner shadow-gray-500/80 transition-all duration-75 ease-in disabled:bg-textDisabled enabled:cursor-pointer bg-textWhite text-black enabled:hover:animate-pulse"
    :class="{
      'bg-accentGreen! text-black': props.isCorrect,
      'bg-accentYellow!': props.isSelected && !props.isCorrect,
    }"
  >
    <slot></slot>
  </button>
</template>
