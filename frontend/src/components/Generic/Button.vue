<script setup>
import { defineProps } from "vue";

const props = defineProps({
  title: String,
  class: String,
  disabled: Boolean,
});

const emit = defineEmits(["click"]);
</script>

<template>
  <button :disabled="props.disabled" @click="emit('click')" :class="'cursor-pointer disabled:cursor-default bg-primary disabled:bg-textDisabled! font-bold text-white py-2 px-10 rounded-full drop-shadow-lg transition-all duration-300 ease-out enabled:hover:scale-105 enabled:hover:shadow-2xl enabled:active:scale-95 disabled:transition-none' + ' ' + props.class">
    {{ title }}
  </button>
</template>
