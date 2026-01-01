<script setup>
const props = defineProps({
  class: String,
});
</script>

<template>
  <div class="rounded-md border border-white/25 text-textWhite p-3 drop-shadow-2xl backdrop-blur-xl bg-bgDark/25 bg-opacity-5" :class="props.class">
    <slot></slot>
  </div>
</template>
