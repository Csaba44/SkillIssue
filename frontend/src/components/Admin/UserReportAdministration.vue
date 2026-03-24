<script setup>
import { ref, computed } from 'vue';
import UserReportItem from "./UserReportItem.vue";
import api from "../../config/api";
import { toast } from "vue-sonner";

const props = defineProps({
    reports: Array
});
const emit = defineEmits(['refresh', 'deleteReport']);

const searchQuery = ref("");
const selectedReport = ref(null);
const isModalOpen = ref(false);
const isSubmitting = ref(false);

const adminNote = ref("");
const activeStatus = ref("");

const filteredReports = computed(() => {
    const order = { 'Investigating': 1, 'Open': 2, 'Closed': 3 };

    return props.reports
        .filter(r => {
            const search = searchQuery.value.toLowerCase();
            return !search ||
                r.user_reported?.name.toLowerCase().includes(search) ||
                r.user_reporting?.name.toLowerCase().includes(search) ||
                r.comment.toLowerCase().includes(search);
        })
        .sort((a, b) => (order[a.status] || 99) - (order[b.status] || 99));
});

const openDetails = async (report) => {
    selectedReport.value = report;
    adminNote.value = "";
    activeStatus.value = report.status;
    isModalOpen.value = true;

    if (report.status == 'Open') {
        activeStatus.value = 'Investigating';
        await saveUpdate('Investigating');
    }
};

const saveUpdate = async (statusOverride = null) => {
    const statusToSave = statusOverride || activeStatus.value;
    if (!statusOverride) isSubmitting.value = true;

    const finalComment = adminNote.value
        ? `${selectedReport.value.comment} | Admin: ${adminNote.value}`
        : selectedReport.value.comment;

    try {
        await api.put(`/api/user-reports/${selectedReport.value.id}`, {
            status: statusToSave,
            comment: finalComment,
            user_id: selectedReport.value.user_id,
            game_match_id: selectedReport.value.game_match_id,
            round_number: selectedReport.value.round_number
        });

        if (!statusOverride) {
            toast.success("Játékos jelentés frissítve!");
            isModalOpen.value = false;
        }
        emit('refresh');
    } catch (error) {
        console.error("Hiba:", error.response?.data);
        if (!statusOverride) toast.error("Hiba történt a mentéskor.");
    } finally {
        isSubmitting.value = false;
    }
};

const copyMatchId = async () => {
    if (!selectedReport.value?.game_match) return;
    try {
        await navigator.clipboard.writeText(selectedReport.value.game_match.match_uuid);
        toast.success("Match UUID kimásolva!");
    } catch (err) {
        toast.error("Másolás sikertelen.");
    }
};
</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="relative w-full lg:w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/20"></i>
                <input v-model="searchQuery" type="text" placeholder="Játékos vagy indok keresése..."
                    class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-white outline-none focus:border-red-500/50 transition-all" />
            </div>
            <div class="text-white/40 text-sm font-medium">
                Összesen: <span class="text-white">{{ reports.length }}</span> bejelentett játékos
            </div>
        </div>

        <div class="grid gap-4">
            <div v-if="filteredReports.length > 0" class="grid gap-3">
                <UserReportItem v-for="report in filteredReports" :key="report.id" :report="report"
                    :class="{ 'opacity-50 grayscale': report.status == 'Closed' }" @view="openDetails"
                    @delete="(id) => emit('deleteReport', id)" />
            </div>
            <div v-else class="py-20 text-center border border-dashed border-white/5 rounded-3xl bg-white/[0.01]">
                <p class="text-white/20 font-medium">Nincs aktív játékos jelentés.</p>
            </div>
        </div>

        <div v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md">
            <div
                class="bg-bgDark border border-white/10 w-full max-w-xl rounded-3xl p-8 shadow-2xl overflow-y-auto max-h-[95vh]">

                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="fa-solid fa-user-shield text-amber-500"></i> Játékos jelentés
                        </h2>
                        <div class="mt-2">
                            <span
                                class="text-[10px] uppercase font-black px-2 py-0.5 rounded bg-white/5 text-white/40 tracking-widest">
                                Állapot: {{ selectedReport.status }}
                            </span>
                        </div>
                    </div>
                    <button @click="copyMatchId" title="Match UUID másolása"
                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:text-white transition-all">
                        <i class="fa-solid fa-gamepad"></i>
                    </button>
                </div>

                <div class="space-y-6 text-left">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1">Bejelentett</label>
                            <p class="text-white font-medium">{{ selectedReport.user_reported?.name }}</p>
                        </div>
                        <div>
                            <label
                                class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1">Forduló</label>
                            <p class="text-white font-medium">{{ selectedReport.round_number }}. kör</p>
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2">Bejelentő
                            indoklása</label>
                        <div
                            class="text-amber-200/60 bg-amber-500/5 p-4 rounded-xl border border-amber-500/10 italic text-sm">
                            "{{ selectedReport.comment }}"
                        </div>
                    </div>

                    <div>
                        <label class="text-white/20 text-[10px] uppercase font-bold tracking-widest block mb-2">Admin
                            megjegyzés</label>
                        <textarea v-model="adminNote" placeholder="Belső jegyzet a kivizsgálásról..." rows="3"
                            class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white text-sm outline-none focus:border-amber-500/40 transition-all resize-none" />
                    </div>

                    <div>
                        <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-3">Státusz
                            váltás</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="activeStatus = 'Open'"
                                :class="activeStatus == 'Open' ? 'bg-red-500 text-white' : 'bg-white/5 text-white/40'"
                                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all">Nyitott</button>
                            <button @click="activeStatus = 'Investigating'"
                                :class="activeStatus == 'Investigating' ? 'bg-amber-500 text-black' : 'bg-white/5 text-white/40'"
                                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all">Vizsgálat</button>
                            <button @click="activeStatus = 'Closed'"
                                :class="activeStatus == 'Closed' ? 'bg-accentGreen text-black' : 'bg-white/5 text-white/40'"
                                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all">Lezárva</button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-10 pt-6 border-t border-white/5">
                    <button @click="emit('deleteReport', selectedReport.id); isModalOpen = false"
                        class="text-red-500/30 hover:text-red-500 text-[10px] font-bold uppercase transition-colors">
                        <i class="fa-solid fa-trash mr-1"></i> Elvetés
                    </button>
                    <div class="flex gap-4">
                        <button @click="isModalOpen = false"
                            class="px-6 py-2 text-white/40 font-bold uppercase text-xs">Bezárás</button>
                        <button @click="saveUpdate()" :disabled="isSubmitting"
                            class="bg-accentGreen text-black font-extrabold px-8 py-2 rounded-full text-xs hover:scale-105 transition-all shadow-lg shadow-accentGreen/20">
                            {{ isSubmitting ? 'Mentés...' : 'Rögzítés' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>