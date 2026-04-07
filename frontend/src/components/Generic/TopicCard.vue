<script setup>
import { defineProps } from "vue";

const typeColors = {
  literature: { bg: "bg-accentYellow", stroke: "strokeYellow", iconColor: "#988433" },
  history: { bg: "bg-accentGreen", stroke: "strokeGreen", iconColor: "#02805D" },
  grammar: { bg: "bg-accentPurple", stroke: "strokePurple", iconColor: "#6146AC" },
};

const props = defineProps({
  isAnimated: {
    type: Boolean,
    default: false,
  },
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
  <div
    class="group shrink-0 relative m-1 flex items-center justify-center h-20 w-40 2xl:h-30 2xl:w-50 rounded-2xl overflow-hidden transition-all duration-500 ease-out hover:-translate-y-2 hover:scale-[1.04] hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)]"
    :class="(typeColors[props.type].bg, isAnimated && 'h-28 w-56')"
  >
    <i
      class="absolute z-5 text-6xl 2xl:text-8xl transition-all duration-500 ease-out group-hover:scale-125 group-hover:rotate-12 group-hover:opacity-80"
      :class="icon"
      :style="{
        color: typeColors[type]?.iconColor,
        top: `${props.iconTop}px`,
        left: `${props.iconLeft}px`,
        rotate: `${props.iconRotate}deg`,
      }"
    ></i>
    <p class="font-bold text-white text-center z-10 2xl:text-2xl text-lg" :class="typeColors[props.type].stroke">
      {{ text }}
    </p>
  </div>
</template>
