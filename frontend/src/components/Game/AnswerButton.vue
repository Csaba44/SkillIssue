<script setup>
const props = defineProps({
  disabled: Boolean,
  isSelected: Boolean,
});
const emit = defineEmits(["click"]);
</script>

<template>
  <button @click="emit('click')" :disabled="props.disabled" :class="props.isSelected && 'bg-accentYellow!'" class="disabled:bg-textDisabled enabled:cursor-pointer bg-textWhite text-black w-full rounded-xl p-2 py-3 text-xl shadow-inner shadow-gray-500/80 transition-all enabled:hover:animate-pulse duration-75 ease-in">
    <slot></slot>
  </button>
</template>
