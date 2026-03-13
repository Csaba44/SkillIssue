<script setup>
const props = defineProps({
  message: {
    type: String,
    required: false,
    default: "Betöltés...",
  },
});
</script>

<template>
  <div class="flex flex-col items-center gap-4 text-white/60">
    <i class="fa-solid fa-spinner animate-spin text-4xl"></i>
    <p>{{ props.message }}</p>
  </div>
</template>
