<script setup>
import { defineProps } from "vue";

const typeColors = {
  literature: { bg: "bg-accentYellow", stroke: "strokeYellow", iconColor: "#988433" },
  history: { bg: "bg-accentGreen", stroke: "strokeGreen", iconColor: "#02805D" },
  grammar: { bg: "bg-accentPurple", stroke: "strokePurple", iconColor: "#6146AC" },
};

const props = defineProps({
  icon: {
    type: String,
    default: "fa-solid fa-flag",
  },
  iconTop: {
    type: Number,
    default: 12,
  },
  iconLeft: {
    type: Number,
    default: -20,
  },
  iconRotate: {
    type: Number,
    default: 0,
  },
  type: {
    type: String,
    default: "history",
  },
  text: {
    type: String,
    default: "Téma",
  },
});
</script>

<template>
  <div class="relative overflow-hidden flex items-center m-1 xl:h-30 xl:w-50 h-20 w-40 justify-center rounded-2xl" :class="typeColors[props.type].bg">
    <i class="absolute z-10 xl:text-8xl text-6xl" :class="icon" :style="{ color: typeColors[type]?.iconColor, top: `${props.iconTop}px`, left: `${props.iconLeft}px`, rotate: `${props.iconRotate}deg` }"></i>
    <p class="font-bold text-white text-center z-50 xl:text-2xl text-lg" :class="typeColors[props.type].stroke">
      {{ text }}
    </p>
  </div>
</template>
