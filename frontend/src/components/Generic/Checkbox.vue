<script setup>
import { ref } from "vue";

const checked = ref(false);

const emit = defineEmits(["state-change"]);

const checkboxClick = () => {
  checked.value = !checked.value;
  emit("state-change", checked);
};
</script>

<template>
  <div class="flex gap-2 items-center justify-start">
    <div @click="checkboxClick" class="shrink-0 bg-bgDark cursor-pointer border border-white/25 rounded-lg w-7 aspect-square flex items-center justify-center">
      <span v-if="checked" class="inline-flex animate-check-reveal">
        <i class="fa-solid fa-check text-sm text-accentGreen"></i>
      </span>
    </div>

    <div class="min-w-0">
      <slot></slot>
    </div>
  </div>
</template>
