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

// XP Progress számítás
const xpProgress = computed(() => {
    if (!profile.value || !profile.value.next_level) return 100;
    const currentXp = profile.value.xp;
    const minXp = profile.value.level.min_xp;
    const maxXp = profile.value.next_level.min_xp;

    const progress = ((currentXp - minXp) / (maxXp - minXp)) * 100;
    return Math.min(Math.max(progress, 0), 100);
});

// Chart adatok
const series = computed(() => [{
    name: "Elo",
    data: matches.value.map((match) => match.elo_after)
}]);

const categories = computed(() => matches.value.map((match) => {
    const date = new Date(match.created_at);
    return new Intl.DateTimeFormat('hu-HU', {
        month: 'short',
        day: 'numeric'
    }).format(date);
}));

const options = computed(() => ({
    chart: {
        height: 350,
        type: 'line',
        background: 'transparent',
        toolbar: { show: true },
        foreColor: '#94a3b8'
    },
    colors: ['#a855f7'],
    stroke: { curve: 'smooth', width: 4 },
    title: {
        text: 'Elo történet',
        style: { color: '#ffffff', fontSize: '18px', fontWeight: '600' }
    },
    grid: { borderColor: 'rgba(255, 255, 255, 0.05)' },
    xaxis: { categories: categories.value },
    yaxis: { forceNiceScale: true },
    theme: { mode: 'dark' },
    markers: {
        size: 5,
        colors: ['#a06bf9'],
        strokeColors: '#fff',
        strokeWidth: 2
    }
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
}

onBeforeMount(getData);
</script>

<template>
    <ProtectedPageContainer class="relative overflow-hidden">
        <i class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>

        <Navbar minimal />

        <section v-if="profile" class="relative z-10 mt-20 max-w-6xl mx-auto px-4 text-textWhite">
            <div class="mb-8">
                <h2 class="text-4xl font-bold">{{ profile.name }} profilja</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-accentGreen/20 flex items-center justify-center text-accentGreen text-3xl">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-textWhite/50 text-sm uppercase tracking-wider font-semibold">Aktivitási Streak</p>
                        <h4 class="text-3xl font-bold">{{ profile.streak_count }} nap</h4>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-accentOrange/20 flex items-center justify-center text-accentOrange text-3xl">
                        <i class="fa-solid fa-fire-flame-curved"></i>
                    </div>
                    <div>
                        <p class="text-textWhite/50 text-sm uppercase tracking-wider font-semibold">Győzelmi sorozat</p>
                        <h4 class="text-3xl font-bold">{{ profile.winstreak_count || 0 }} win</h4>
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
                        <span class="text-textWhite/30 text-lg"> / {{ profile.next_level?.min_xp || 'Max' }} XP</span>
                    </div>
                </div>

                <div class="w-full h-5 bg-white/10 rounded-full overflow-hidden p-[3px]">
                    <div class="h-full rounded-full bg-gradient-to-r from-accentYellow via-accentGreen to-emerald-400 transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(234,179,8,0.2)]"
                        :style="{ width: `${xpProgress}%` }"></div>
                </div>
                
                <div class="flex justify-between mt-3 text-xs text-textWhite/40 font-bold tracking-widest">
                    <span class="uppercase">Level {{ profile.level.level }}</span>
                    <span v-if="profile.next_level" class=" tracking-normal">
                        Még {{ profile.next_level.min_xp - profile.xp }} XP kell a szintlépéshez!
                    </span>
                    <span class="uppercase">Level {{ profile.next_level?.level || 'MAX' }}</span>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl mb-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-accentPurple"></i>
                        Elo fejlődés
                    </h3>
                </div>

                <div v-if="matches.length > 0">
                    <VueApexCharts type="line" height="350" :options="options" :series="series" />
                </div>

                <div v-else
                    class="h-[350px] flex items-center justify-center text-white/20 border-2 border-dashed border-white/5 rounded-2xl text-center">
                    <div>
                        <i class="fa-solid fa-gamepad text-4xl mb-3 block opacity-20"></i>
                        Még nincsenek lejátszott meccsek.
                    </div>
                </div>
            </div>
        </section>
    </ProtectedPageContainer>
</template>