<script setup>
import { computed, ref } from "vue";
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

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
    <Widget class="rounded-full! px-10 py-4 w-full max-w-6xl grid grid-cols-1 md:grid-cols-3 items-center">
      <div class="text-center md:text-left">
        <h1 class="text-xl text-white font-bold">Skill<span class="text-primary">Issue</span></h1>
      </div>

      <div class="hidden md:flex justify-center">
        <div class="flex flex-col items-center w-80 relative group">
          <p class="text-sm text-white/60">
            {{ user.rank.name }} • <span class="text-white font-semibold">{{ user.elo }}</span> Elo
          </p>

          <div class="w-full h-2 bg-white/10 rounded-full mt-2 overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-accentPurple via-accentGreen to-accentGreen transition-all duration-500"
              :style="{
                width: ((user.elo - user.rank.min_elo) / (user.next_rank.min_elo - user.rank.min_elo)) * 100 + '%',
              }"
            ></div>
          </div>

          <div class="absolute top-10 bg-bgDark text-textWhite text-xs px-3 py-1 rounded-md opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 pointer-events-none whitespace-nowrap">{{ eloToNextRank }} Elo a következő rangig</div>
        </div>
      </div>

      <div class="flex justify-center md:justify-end items-center gap-6 mt-4 md:mt-0">
        <div class="flex items-center gap-2">
          <span class="text-lg font-medium">{{ user.streak_count }}</span>
          <i class="fa-regular fa-fire text-primary text-lg"></i>
        </div>

        <div>
          <span class="text-lg font-medium">{{ user.name }}</span>
        </div>
      </div>
    </Widget>
  </div>
</template>
