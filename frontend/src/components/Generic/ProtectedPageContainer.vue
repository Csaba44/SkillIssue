<script setup>
const props = defineProps({
  class: String,
});
</script>

<template>
  <div class="bg-bgDark w-screen min-h-screen p-0 md:p-8 flex">
    <div class="bg-bgAlternate w-full md:rounded-md border md:border-white/25 min-h-full p-2 md:p-5" :class="props.class">
      <slot></slot>
    </div>
  </div>
</template>
