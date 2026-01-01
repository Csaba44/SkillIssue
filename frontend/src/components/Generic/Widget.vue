<script setup>
const props = defineProps({
  class: String,
  title: String,
});
</script>

<template>
  <div class="rounded-3xl border border-white/25 text-textWhite p-3 drop-shadow-2xl backdrop-blur-xl bg-bgDark/25 bg-opacity-5" :class="props.class">
    <h2 v-if="title" class="font-bold text-xl">{{ title }}</h2>
    <slot></slot>
  </div>
</template>
