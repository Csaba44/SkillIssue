<script setup>
import { computed } from "vue";

const props = defineProps({
  reason: {
    type: String,
    default: "Ismeretlen",
  },
  releaseDate: {
    type: String,
    default: null,
  },
});

const formattedDate = computed(() => {
  if (!props.releaseDate) return "Ismeretlen";

  const date = new Date(props.releaseDate + "Z");

  return date.toLocaleString("hu-HU", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
});
</script>

<template>
  <div class="min-h-screen w-full flex items-center justify-center bg-bgDark px-4 py-10">
    <div class="w-full max-w-lg flex flex-col items-center text-center gap-6">
      <div class="w-20 h-20 rounded-full bg-red-500/10 border border-red-500/30 flex items-center justify-center">
        <i class="fa-solid fa-ban text-error text-4xl"></i>
      </div>

      <div class="flex flex-col gap-2">
        <h1 class="text-2xl sm:text-3xl font-bold text-textWhite">Ki lettél tiltva</h1>
        <p class="text-textGray text-sm sm:text-base">A hozzáférésed a <span class="text-textWhite font-semibold">SkillIssue</span> alkalmazáshoz fel lett függesztve.</p>
      </div>

      <div class="w-full flex flex-col gap-3">
        <div class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 flex items-start gap-4 text-left">
          <i class="fa-solid fa-circle-exclamation text-warning text-lg mt-0.5"></i>
          <div class="flex flex-col gap-0.5">
            <span class="text-xs text-textGray uppercase tracking-widest">Indok</span>
            <span class="text-textWhite text-sm sm:text-base font-medium">{{ reason }}</span>
          </div>
        </div>

        <div class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 flex items-start gap-4 text-left">
          <i class="fa-solid fa-clock text-accentYellow text-lg mt-0.5"></i>
          <div class="flex flex-col gap-0.5">
            <span class="text-xs text-textGray uppercase tracking-widest">Feloldás ideje</span>
            <span class="text-textWhite text-sm sm:text-base font-medium">{{ formattedDate }}</span>
          </div>
        </div>
      </div>

      <p class="text-xs text-textGray">Ha úgy gondolod, hogy ez tévedés, kérjük vedd fel a kapcsolatot egy adminisztrátorral: support@skillissue.hu</p>
    </div>
  </div>
</template>
