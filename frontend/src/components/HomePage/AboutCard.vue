<script setup>
import { defineProps } from "vue";

const typeColors = {
    yellow: { colorVar: "var(--color-accentYellow)", iconColor: "#988433" },
    green: { colorVar: "var(--color-accentGreen)", iconColor: "#02805D" },
    purple: { colorVar: "var(--color-accentPurple)", iconColor: "#6146AC" },
    orange: { colorVar: "var(--color-accentOrange)", iconColor: "#993418" },
};

const props = defineProps({
    icon: { type: String, default: "fa-solid fa-flag" },
    type: { type: String, default: "purple" },
    title: { type: String, default: "Cím" },
    desc: { type: String, default: "Leírás" },
});
</script>

<template>
    <div
        class="bg-white  relative flex flex-col mx-5 md:h-110 md:w-80 2xl:mx-auto my-5 h-100 w-75 items-center justify-between xl:h-100 xl:w-70 2xl:h-120 2xl:w-90 rounded-4xl overflow-hidden shadow-md">
        <div class="absolute bottom-20 left-0 w-full h-full">
            <svg class="w-full h-full" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                <path :fill="typeColors[props.type].colorVar" d="
            M120 80
            C40 140, 60 280, 180 300
            C260 360, 380 340, 460 280
            C540 220, 560 120, 460 90
            C420 40, 260 20, 180 60
            C150 40, 140 60, 120 80
            Z
          " />
            </svg>
        </div>

        <i class="absolute top-20 text-6xl md:top-23 md:text-7xl xl:top-20 2xl:top-25 xl:text-7xl 2xl:text-8xl z-10" :class="props.icon"
            :style="{ color: typeColors[props.type].iconColor }"></i>

        <p class="z-10  font-bold text-black text-4xl text-center mt-50 md:mt-60 xl:mt-50 2xl:mt-70">
            {{ props.title }}
        </p>

        <div class="px-3 bottom-5 w-full">
            <p class="z-10 text-lg text-gray-500 text-center mb-8 px-2 
                  overflow-hidden overflow-ellipsis break-words
                  max-h-24">
                {{ props.desc }}adsbhasbdasbdbasbdbsahdbashdbahasbdbhasbdhsba
            </p>
        </div>

    </div>
</template>
