<script setup>
import { computed, ref } from "vue";
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

const eloPercentArr = computed(() => {
  if (!user.value?.rank || !user.value?.next_rank) {
    return [0, 0, 0];
  }

  const currentElo = user.value.elo;
  const min = user.value.rank.min_elo;
  const max = user.value.next_rank.min_elo;

  if (currentElo <= min) return [0, 0, 0];
  if (currentElo >= max) return [100, 100, 100];

  const progress = ((currentElo - min) / (max - min)) * 100;

  const chunkSize = 100 / 3;
  let remaining = progress;

  return Array.from({ length: 3 }, () => {
    const value = Math.min(chunkSize, remaining);
    remaining -= value;
    return Math.max(0, (value / chunkSize) * 100);
  });
});

const eloToNextRank = computed(() => user.value.next_rank.min_elo - user.value.elo);
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
        <div class="flex flex-col cursor-pointer relative group">
          <p>Elo {{ user.elo }}</p>

          <div class="flex justify-between gap-1">
            <div v-for="(percentChunk, idx) in eloPercentArr" :key="idx" class="w-1/3 h-1 bg-textWhite rounded-full overflow-hidden">
              <span class="h-full bg-accentGreen block" :style="{ width: percentChunk + '%' }"></span>
            </div>
          </div>
          
          <div class="absolute left-1/2 top-9 -translate-x-1/2 bg-bgDark text-textWhite text-xs px-3 py-1 rounded-md opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 pointer-events-none whitespace-nowrap">-{{ eloToNextRank }} Elo</div>
        </div>

        <div>
          <span class="text-lg font-medium">{{ user.streak_count }}</span>
          <i class="fa-regular fa-fire text-primary text-lg"></i>
        </div>
      </div>
    </Widget>
  </div>
</template>
