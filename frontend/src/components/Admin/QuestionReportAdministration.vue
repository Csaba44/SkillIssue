<script setup>
import { ref, computed } from 'vue';
import QuestionReportItem from "./QuestionReportItem.vue";
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
                r.question?.question.toLowerCase().includes(search) ||
                r.user?.name.toLowerCase().includes(search);
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

    const finalComment = adminNote.value;

    try {
        await api.put(`/api/question-reports/${selectedReport.value.id}`, {
            status: statusToSave,
            comment: finalComment,
            question_id: selectedReport.value.question_id,
            user_id: selectedReport.value.user_id
        });

        if (!statusOverride) {
            toast.success("Jelentés sikeresen frissítve!");
            isModalOpen.value = false;
        }
        emit('refresh');
    } catch (error) {
        console.error("Hiba a mentés során:", error.response?.data);
        if (!statusOverride) toast.error("Hiba történt a mentéskor.");
    } finally {
        isSubmitting.value = false;
    }
};

const copyToClipboard = async () => {
    if (!selectedReport.value || !selectedReport.value.question) return;

    const textToCopy = selectedReport.value.question.question;

    try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(textToCopy);
        } else {
            const textArea = document.createElement("textarea");
            textArea.value = textToCopy;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }
        toast.success("Kérdés a vágólapra másolva!");
    } catch (err) {
        toast.error("Sikertelen másolás.");
    }
};
</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="relative w-full lg:w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/20"></i>
                <input v-model="searchQuery" type="text" placeholder="Szűrés (kérdés, felhasználó...)"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-white outline-none focus:border-red-500/50 transition-all" />
            </div>
            <div class="text-white/40 text-sm font-medium">
                Összesen: <span class="text-white">{{ reports.length }}</span> bejelentés
            </div>
        </div>

        <div class="grid gap-4">
            <div v-if="filteredReports.length > 0" class="grid gap-3">
                <QuestionReportItem v-for="report in filteredReports" :key="report.id" :report="report"
                    :class="{ 'opacity-50 grayscale': report.status == 'Closed' }" @view="openDetails" />
            </div>

            <div v-else class="py-20 text-center border border-dashed border-white/5 rounded-3xl bg-white/[0.01]">
                <i class="fa-solid fa-check-double text-4xl text-white/10 mb-4 block"></i>
                <p class="text-white/20 font-medium">Nincs aktív jelentés.</p>
            </div>
        </div>

        <div v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md">
            <div
                class="bg-bgDark border border-white/10 w-full max-w-xl rounded-3xl p-8 shadow-2xl overflow-y-auto max-h-[95vh]">

                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Bejelentés kezelése
                        </h2>
                        <div class="mt-2 flex gap-2">
                            <span
                                class="text-[10px] uppercase font-black px-2 py-0.5 rounded bg-white/5 text-white/40 tracking-widest">
                                Aktuális státusz: {{ selectedReport.status }}
                            </span>
                        </div>
                    </div>
                    <button @click="copyToClipboard"
                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:text-white transition-all">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>

                <div class="space-y-6 text-left">
                    <div>
                        <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2">Érintett
                            kérdés</label>
                        <div class="text-white bg-white/5 p-4 rounded-xl border border-white/5 text-sm leading-relaxed">
                            {{ selectedReport.question?.question }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2">Felhasználó
                            indoklása</label>
                        <div
                            class="text-red-200/60 bg-red-500/5 p-4 rounded-xl border border-red-500/10 italic text-sm">
                            "{{ selectedReport.comment || 'Nincs indoklás.' }}"
                        </div>
                    </div>

                    <div>
                        <label class="text-white/20 text-[10px] uppercase font-bold tracking-widest block mb-2">Admin
                            megjegyzés (Saját jegyzet)</label>
                        <textarea v-model="adminNote" placeholder="Ide írd a belső jegyzeteidet..." rows="3"
                            class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white text-sm outline-none focus:border-accentGreen/40 transition-all resize-none" />
                    </div>

                    <div>
                        <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-3">Új
                            állapot beállítása</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="activeStatus = 'Open'"
                                :class="activeStatus == 'Open' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white/5 text-white/40 hover:bg-white/10'"
                                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all">
                                <i class="fa-solid fa-envelope-open mb-1 block"></i> Nyitott
                            </button>

                            <button @click="activeStatus = 'Investigating'"
                                :class="activeStatus == 'Investigating' ? 'bg-amber-500 text-black shadow-lg shadow-amber-500/20' : 'bg-white/5 text-white/40 hover:bg-white/10'"
                                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all">
                                <i class="fa-solid fa-magnifying-glass mb-1 block"></i> Vizsgálat
                            </button>

                            <button @click="activeStatus = 'Closed'"
                                :class="activeStatus == 'Closed' ? 'bg-accentGreen text-black shadow-lg shadow-accentGreen/20' : 'bg-white/5 text-white/40 hover:bg-white/10'"
                                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all">
                                <i class="fa-solid fa-check-double mb-1 block"></i> Lezárva
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-10 pt-6 border-t border-white/5">
                    <button @click="emit('deleteReport', selectedReport.id); isModalOpen = false"
                        class="text-red-500/30 hover:text-red-500 text-[10px] font-bold uppercase transition-colors">
                        <i class="fa-solid fa-trash mr-1"></i> Törlés
                    </button>
                    <div class="flex gap-4">
                        <button @click="isModalOpen = false"
                            class="px-6 py-2 text-white/40 font-bold uppercase text-xs">Mégse</button>
                        <button @click="saveUpdate()" :disabled="isSubmitting"
                            class="bg-accentGreen text-black font-extrabold px-8 py-2 rounded-full text-xs hover:scale-105 transition-all shadow-lg shadow-accentGreen/20">
                            {{ isSubmitting ? 'Mentés...' : 'Változtatások mentése' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>