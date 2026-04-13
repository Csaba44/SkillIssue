<script setup>
const props = defineProps({
  report: Object,
});
const emit = defineEmits(["view", "delete"]);

const statusConfig = {
  Open: {
    icon: "fa-solid fa-triangle-exclamation",
    colorClass: "bg-red-500/10 text-red-500",
  },
  Investigating: {
    icon: "fa-solid fa-magnifying-glass",
    colorClass: "bg-yellow-500/10 text-yellow-500",
  },
  Closed: {
    icon: "fa-solid fa-envelope",
    colorClass: "bg-green-500/10 text-green-500",
  },
};
</script>

<template>
  <div class="bg-white/3 border border-white/5 p-5 rounded-xl flex justify-between items-center group hover:bg-white/5 transition-all sm:flex-row flex-col">
    <div class="flex items-center gap-6 text-left sm:flex-row flex-col">
      <div :class="['w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300', statusConfig[report.status]?.colorClass || 'bg-amber-500/10 text-amber-500']">
        <i :class="statusConfig[report.status]?.icon || 'fa-solid fa-user-shield'"></i>
      </div>

      <div class="flex flex-col">
        <h3 class="text-md font-medium text-white/80 group-hover:text-white transition-colors">Bejelentett: {{ report.user_reported?.name || "Ismeretlen játékos" }}</h3>
        <div class="flex gap-3 mt-1">
          <span class="text-xs text-white/30 italic"> Bejelentő: {{ report.user_reporting?.name || "Rendszer" }} </span>
          <span class="text-xs text-accentPurple/60 font-mono"> Match: #{{ report.game_match?.match_uuid?.substring(0, 8) || "N/A" }} </span>
        </div>
      </div>
    </div>

    <div class="flex gap-2 sm:mt-0 mt-5">
      <button @click="emit('view', report)" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white/60 hover:text-white transition-all text-xs font-bold uppercase active:scale-95 cursor-pointer">Részletek</button>
      <button @click="emit('delete', report.id)" class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-red-500/20 text-white/20 hover:text-red-500 transition-all active:scale-90">
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>
  </div>
</template>
