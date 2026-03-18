<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useMatchmakingStore } from "../../stores/MatchmakingStore";

const mm = useMatchmakingStore();

const seconds = ref(15);
let interval = null;

onMounted(() => {
  interval = setInterval(() => {
    if (seconds.value > 0) {
      seconds.value--;
    }
  }, 1000);
});

onUnmounted(() => {
  clearInterval(interval);
});
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="w-105 rounded-xl bg-[#0f0f0f] border border-white/10 shadow-2xl p-8 text-center text-white">
      <h2 class="text-2xl font-bold mb-2 tracking-wide">Megtaláltuk ellenfeled</h2>

      <p class="text-white/60 mb-6">Fogadd el a meccset</p>
      <button @click="mm.confirm()" :disabled="mm.isConfirmed" class="w-full py-4 rounded-lg font-bold text-lg text-black transition-all duration-200 bg-green-500 hover:bg-green-400 hover:scale-[1.02] disabled:bg-green-800 disabled:text-white/50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:bg-green-800">{{ mm.isConfirmed ? "ELFOGADVA" : "ELFOGADÁS" }}</button>

      <div class="mt-6 text-sm text-white/50">Automatikus visszalépés: {{ seconds }}s</div>
    </div>
  </div>
</template>
