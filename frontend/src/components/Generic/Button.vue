<script setup>
import { defineProps } from "vue";

const props = defineProps({
  title: String,
  class: String,
});

const emit = defineEmits(["click"]);
</script>

<template>
  <button @click="emit('click')" :class="'cursor-pointer bg-primary font-bold text-white py-2 px-10 rounded-full drop-shadow-lg transition-all duration-300 ease-out hover:scale-105 hover:shadow-2xl active:scale-95' + ' ' + props.class">
    {{ title }}
  </button>
</template>
