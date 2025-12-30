<script setup>
const model = defineModel();

const checkboxClick = () => {
  model.value = !model.value;
};
</script>

<template>
  <div class="flex gap-2 items-center justify-start">
    <div @click="checkboxClick" class="shrink-0 bg-bgDark cursor-pointer border border-white/25 rounded-lg w-6 aspect-square flex items-center justify-center">
      <span v-if="model" class="inline-flex animate-check-reveal">
        <i class="fa-solid fa-check text-sm text-accentGreen"></i>
      </span>
    </div>

    <div class="min-w-0">
      <slot></slot>
    </div>
  </div>
</template>
