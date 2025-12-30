<script setup>
import { defineProps } from "vue";

const typeColors = {
    history: { bg: "bg-accentYellow", stroke: "strokeYellow", strokeColor: "#988433" },
    grammar: { bg: "bg-accentGreen", stroke: "strokeGreen", strokeColor: "#02805D" },
    literature: { bg: "bg-accentPurple", stroke: "strokePurple", strokeColor: "#6146AC" },
};

const props = defineProps({
    icon: String,
    iconTop: Number,
    iconLeft: Number,
    type: String,
    text: String
});

const getStrokeColor = (type) => typeColors[type]?.strokeColor || "#ffffff";
</script>

<template>
    <div class="relative overflow-hidden flex items-center m-1 h-30 w-70 justify-center rounded-2xl"
        :class="typeColors[props.type].bg">
        <i class="absolute z-10 text-8xl"
            :class="[icon, `top-[${props.iconTop}px]`, `left-[${props.iconLeft}px]`]"
            :style="{ color: getStrokeColor(props.type) }"></i>
        <p class="font-bold text-white text-center z-50 text-3xl" :class="typeColors[props.type].stroke">
            {{ text }}
        </p>
    </div>
</template>
