<script setup>
import { ref } from "vue";
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const { user } = storeToRefs(userStore);
const selected = ref(false);

const emit = defineEmits(["selected-gamemode-change"]);

const props = defineProps({
  isMatchmaking: Boolean,
});

const setSelected = (mode) => {
  if (props.isMatchmaking) return;
  if (selected === mode) return;
  selected.value = mode;
  emit("selected-gamemode-change", mode);
};
</script>

<template>
  <div class="w-full flex justify-center items-center">
    <Widget class="rounded-full! px-10 gap-5 grid grid-rows-2 grid-cols-1 sm:grid-rows-1 sm:grid-cols-2 md:grid-cols-3 items-center justify-center">
      <div class="text-center sm:text-left">
        <h1 class="text-xl text-white font-bold">Skill<span class="text-primary">Issue</span></h1>
      </div>

      <nav class="text-textWhite text-lg flex justify-center items-center gap-5 font-medium text-nowrap">
        <button :disabled="props.isMatchmaking" @click="setSelected('Ranked')" :class="selected == 'Ranked' && 'text-error!'" class="disabled:text-textDisabled cursor-pointer enabled:hover:text-warning enabled:hover:scale-105 transition-all">Ranked</button>
        <button :disabled="props.isMatchmaking" @click="setSelected('Solo')" :class="selected == 'Solo' && 'text-error!'" class="disabled:text-textDisabled cursor-pointer flex gap-1 items-center justify-center enabled:hover:text-warning enabled:hover:scale-105 transition-all">Solo<span class="hidden sm:flex"> gyakorlás</span></button>
      </nav>

      <div class="hidden md:flex gap-5 justify-end">
        <div class="flex flex-col">
          <p>Elo {{ user.elo }}</p>
          <div class="flex justify-between">
            <div class="w-[28%] h-1 bg-textWhite rounded-full flex overflow-hidden">
              <span class="w-full h-full bg-accentGreen"></span>
            </div>
            <div class="w-[28%] h-1 bg-textWhite rounded-full flex overflow-hidden">
              <span class="w-full h-full bg-accentGreen"></span>
            </div>
            <div class="w-[28%] h-1 bg-textWhite rounded-full flex overflow-hidden">
              <span class="w-[50%] h-full bg-accentGreen"></span>
            </div>
          </div>
        </div>
        <div>
          <span class="text-lg font-medium">{{ user.streak_count }}</span>
          <i class="fa-regular fa-fire text-primary text-lg"></i>
        </div>
      </div>
    </Widget>
  </div>
</template>
