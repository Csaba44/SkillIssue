<script setup>
const props = defineProps({
  class: {
    type: String,
    required: false,
    default: ""
  }
});
</script>

<template>
  <div :class="'px-5 md:px-10' + ' ' + props.class">
    <slot></slot>
  </div>
</template>
