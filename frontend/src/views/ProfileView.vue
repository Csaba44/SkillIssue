<script setup>
import { onBeforeMount, ref, computed } from "vue";
import { useRoute } from "vue-router";
import api from "../config/api";
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import VueApexCharts from "vue3-apexcharts";

const route = useRoute();
const profile = ref(null);
const matches = ref([]);

const xpProgress = computed(() => {
  if (!profile.value || !profile.value.next_level) return 100;
  const currentXp = profile.value.xp;
  const minXp = profile.value.level.min_xp;
  const maxXp = profile.value.next_level.min_xp;

  const progress = ((currentXp - minXp) / (maxXp - minXp)) * 100;
  return Math.min(Math.max(progress, 0), 100);
});

// Elo chart datas
const series = computed(() => [
  {
    name: "Elo",
    data: matches.value.map((match) => match.elo_after),
  },
]);

const categories = computed(() =>
  matches.value.map((match) => {
    const date = new Date(match.created_at);
    return new Intl.DateTimeFormat("hu-HU", {
      month: "short",
      day: "numeric",
    }).format(date);
  }),
);

//Elo chart conf
const options = computed(() => ({
  chart: {
    height: 350,
    type: "line",
    background: "transparent",
    toolbar: { show: true },
    foreColor: "#94a3b8",
  },
  colors: ["#a855f7"],
  stroke: { curve: "smooth", width: 4 },
  title: {
    text: "",
    style: { color: "#ffffff", fontSize: "18px", fontWeight: "600" },
  },
  grid: { borderColor: "rgba(255, 255, 255, 0.05)" },
  xaxis: { categories: categories.value },
  yaxis: { forceNiceScale: true },
  theme: { mode: "dark" },
  markers: {
    size: 5,
    colors: ["#a06bf9"],
    strokeColors: "#fff",
    strokeWidth: 2,
  },
}));

const heatmapSeries = computed(() => {
  const months = ["Jan", "Feb", "Márc", "Ápr", "Máj", "Jún", "Júl", "Aug", "Szep", "Okt", "Nov", "Dec"];
  const currentMonth = new Date().getMonth();

  // Only display the last 6 month from now
  const data = [];
  for (let i = 0; i < 6; i++) {
    const monthIdx = (currentMonth - i + 12) % 12;
    data.push({
      name: months[monthIdx],
      data: Array.from({ length: 31 }, (_, day) => ({ x: (day + 1).toString(), y: 0 })),
    });
  }

  matches.value.forEach((match) => {
    const d = new Date(match.created_at);
    const mName = months[d.getMonth()];
    const dayStr = d.getDate().toString();

    const monthRow = data.find((m) => m.name == mName);
    if (monthRow) {
      const dayPoint = monthRow.data.find((p) => p.x == dayStr);
      if (dayPoint) dayPoint.y++;
    }
  });

  return data;
});

const heatmapOptions = computed(() => ({
  chart: {
    height: 250,
    type: "heatmap",
    background: "transparent",
    toolbar: { show: false },
    foreColor: "#94a3b8",
    animations: { enabled: true },
  },
  dataLabels: { enabled: false },
  stroke: {
    show: true,
    width: 1,
    colors: ["#0f172a"],
  },
  plotOptions: {
    heatmap: {
      radius: 2,
      enableShades: false,
      useFillColorAsStroke: false,
      colorScale: {
        ranges: [
          {
            from: 0,
            to: 0,
            name: "Nincs meccs",
            color: "rgba(255, 255, 255, 0.03)",
          },
          {
            from: 1,
            to: 2,
            name: "Kevés",
            color: "rgba(34, 197, 94, 0.4)",
          },
          {
            from: 3,
            to: 5,
            name: "Gyakori",
            color: "rgba(34, 197, 94, 0.7)",
          },
          {
            from: 6,
            to: 100,
            name: "Hardcore",
            color: "#22c55e",
          },
        ],
      },
    },
  },
  grid: {
    show: false,
    padding: {
      right: 20,
    },
  },
  xaxis: {
    type: "category",
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: {
      style: { fontSize: "9px" },
    },
  },
  yaxis: {
    labels: {
      style: { fontSize: "11px", fontWeight: "600" },
    },
  },
  theme: { mode: "dark" },
  tooltip: {
    theme: "dark",
    custom: function ({ seriesIndex, dataPointIndex, w }) {
      const val = w.globals.series[seriesIndex][dataPointIndex];
      return `<div class="px-3 py-2 bg-slate-900 border border-white/10 rounded-lg shadow-xl text-xs">
                <span class="font-bold text-accentGreen">${val} meccs</span>
            </div>`;
    },
  },
}));

const getData = async () => {
  try {
    const resProfile = await api.get(`/api/profiles/${route.params.id}`);
    if (resProfile.status == 200) {
      profile.value = resProfile.data;
      matches.value = resProfile.data.game_matches;
    }
  } catch (error) {
    console.error("Hiba:", error);
  }
};

onBeforeMount(getData);
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i
      class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
    ></i>
    <Navbar minimal />

    <section v-if="profile" class="relative z-10 mt-20 max-w-6xl mx-auto px-4 text-textWhite">
      <p class="text-textWhite/30 mb-3 visible sm:hidden z-0">A legjobb élmény érdekében fordítsd el a kijelződet!</p>
      <div class="mb-8">
        <h2 class="text-4xl font-bold">{{ profile.name }} profilja</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div
          class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl flex items-center gap-5"
        >
          <div
            class="w-16 h-16 rounded-2xl bg-accentGreen/20 flex items-center justify-center text-accentGreen text-3xl"
          >
            <i class="fa-solid fa-calendar-check"></i>
          </div>
          <div>
            <p class="text-textWhite/50 text-sm uppercase tracking-wider font-semibold">Aktivitási Streak</p>
            <h4 class="text-3xl font-bold">{{ profile.streak_count }} nap</h4>
          </div>
        </div>

        <div
          class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl flex items-center gap-5"
        >
          <div
            class="w-16 h-16 rounded-2xl bg-accentOrange/20 flex items-center justify-center text-accentOrange text-3xl"
          >
            <i class="fa-solid fa-fire-flame-curved"></i>
          </div>
          <div>
            <p class="text-textWhite/50 text-sm uppercase tracking-wider font-semibold">Győzelmi sorozat</p>
            <h4 class="text-3xl font-bold">{{ profile.winstreak_count || 0 }} győzelem</h4>
          </div>
        </div>
      </div>

      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-10">
        <div class="flex justify-between items-end mb-4">
          <div>
            <h3 class="text-xl font-semibold flex items-center gap-2">
              <i class="fa-solid fa-star text-accentYellow"></i>
              Szint haladás
            </h3>
          </div>
          <div class="text-right">
            <span class="text-accentYellow font-bold text-xl">{{ profile.xp }}</span>
            <span class="text-textWhite/30 text-lg"> / {{ profile.next_level?.min_xp || "Max" }} XP</span>
          </div>
        </div>

        <div class="w-full h-5 bg-white/10 rounded-full overflow-hidden p-[3px]">
          <div
            class="h-full rounded-full bg-gradient-to-r from-accentYellow via-accentGreen to-emerald-400 transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(234,179,8,0.2)]"
            :style="{ width: `${xpProgress}%` }"
          ></div>
        </div>

        <div class="flex justify-between mt-3 text-xs text-textWhite/40 font-bold tracking-widest">
          <span class="uppercase">Level {{ profile.level.level }}</span>
          <span v-if="profile.next_level" class="tracking-normal hidden sm:flex">
            Még {{ profile.next_level.min_xp - profile.xp }} XP kell a szintlépéshez!
          </span>
        </div>
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-semibold flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-accentPurple"></i>
            Elo történet
          </h3>
        </div>

        <div v-if="matches.length > 0">
          <VueApexCharts type="line" height="350" :options="options" :series="series" />
        </div>

        <div
          v-else
          class="h-[350px] flex items-center justify-center text-white/20 border-2 border-dashed border-white/5 rounded-2xl text-center"
        >
          <div>
            <i class="fa-solid fa-gamepad text-4xl mb-3 block opacity-20"></i>
            Még nincsenek lejátszott meccsek.
          </div>
        </div>
      </div>

      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl mb-10">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-semibold flex items-center gap-2">
            <i class="fa-solid fa-calendar-days text-accentGreen"></i>
            Havi lebontás
          </h3>
        </div>

        <div v-if="matches.length > 0">
          <VueApexCharts type="heatmap" height="350" :options="heatmapOptions" :series="heatmapSeries" />
        </div>

        <div
          v-else
          class="h-[200px] flex items-center justify-center text-white/20 border-2 border-dashed border-white/5 rounded-2xl text-center"
        >
          Nincs elegendő adat az aktivitási térképhez.
        </div>
      </div>
    </section>
  </ProtectedPageContainer>
</template>
