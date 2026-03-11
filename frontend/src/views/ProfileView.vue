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

// Az adatok (series) stílusa (nevek és színek)
const series = computed(() => [{
    name: "Elo",
    data: matches.value.map((match) => match.elo_after)
}]);

const categories = computed(() => matches.value.map((match) => {
    // Ha a created_at string, dátum objektummá alakítjuk
    const date = new Date(match.created_at);
    
    // Magyar formátum: hónap rövidítése + nap
    return new Intl.DateTimeFormat('hu-HU', { 
        month: 'short', 
        day: 'numeric' 
    }).format(date);
}));

// Az options stílusának átírása a sötét témához
const options = computed(() => ({
    chart: {
        height: 350,
        type: 'line',
        background: 'transparent',
        zoom: { enabled: true },
        toolbar: { show: true },
        foreColor: '#94a3b8'
    },
    colors: ['#a855f7'],
    dataLabels: { enabled: false },
    stroke: {
        curve: 'smooth',
        width: 4
    },
    title: {
        text: 'Elo történet',
        align: 'left',
        style: {
            color: '#ffffff',
            fontSize: '18px',
            fontWeight: '600'
        }
    },
    grid: {
        borderColor: 'rgba(255, 255, 255, 0.05)',
        xaxis: { lines: { show: true } },
    },
    xaxis: {
        // Itt most már reaktívan kapja meg az értékeket!
        categories: categories.value, 
        axisBorder: { show: false },
        axisTicks: { show: false }
    },
    yaxis: {
        forceNiceScale: true,
    },
    theme: { mode: 'dark' },
    tooltip: {
        theme: 'dark',
        x: { show: true }
    },
    markers: {
        size: 5,
        colors: ['#a855f7'],
        strokeColors: '#fff',
        strokeWidth: 2,
        hover: { size: 7 }
    }
}));

const getData = async () => {
    try {
        const resProfile = await api.get(`/api/profiles/${route.params.id}`);
        if (resProfile.status != 200) return console.log("error", resProfile);
        profile.value = resProfile.data;
        matches.value = resProfile.data.game_matches;
    } catch (error) {
        console.error("Hiba az adatok lekérésekor:", error);
    }
}

onBeforeMount(getData);
</script>

<template>
    <ProtectedPageContainer class="relative overflow-hidden">
        <i
            class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>

        <Navbar minimal />

        <section v-if="profile" class="relative z-10 mt-20 max-w-6xl mx-auto px-4 text-textWhite">
            <div class="mb-8">
                <h2 class="text-4xl font-bold">{{ profile.name }} profilja</h2>
            </div>

            <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl mb-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-accentPurple"></i>
                        Elo fejlődés
                    </h3>
                    <div class="text-sm text-white/40 italic">Görgess a nagyításhoz</div>
                </div>

                <div v-if="matches.length > 0">
                    <VueApexCharts type="line" height="350" :options="options" :series="series">
                    </VueApexCharts>
                </div>

                <div v-else
                    class="h-[350px] flex items-center justify-center text-white/20 border-2 border-dashed border-white/5 rounded-2xl">
                    Még nincsenek lejátszott meccsek.
                </div>
            </div>

        </section>
    </ProtectedPageContainer>
</template>