<script setup>
const props = defineProps({
  class: String,
});
</script>

<template>
  <div class="bg-bgDark w-screen min-h-screen p-8 flex">
    <div class="bg-bgAlternate w-full rounded-md border border-white/25 min-h-full p-5" :class="props.class">
      <slot></slot>
    </div>
  </div>
</template>
