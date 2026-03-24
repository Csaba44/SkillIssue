<script setup>
import { onBeforeMount, ref } from 'vue';
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import QuestionAdministration from "../components/Admin/QuestionAdministration.vue";
import QuestionReportAdministration from "../components/Admin/QuestionReportAdministration.vue";
import { toast } from "vue-sonner";
import api from "../config/api";

const activeTab = ref('questions');
const questions = ref([]);
const subjects = ref([]);
const reports = ref([]);

const getReports = async () => {
    try {
        const res = await api.get("/api/question-reports");
        reports.value = res.data;
    } catch (error) {
        console.error("Hiba a reportok lekérésekor:", error);
    }
}

const deleteReport = async (id) => {
    try {
        await api.delete(`/api/question-reports/${id}`);
        toast.success("Report sikeresen eltávolítva!");
        getReports();
    } catch (error) {
        toast.error("Hiba a törlés során.");
    }
}

const getQuestions = async () => {
    try {
        const res = await api.get("/api/questions");
        questions.value = res.data;
        console.log(res.data)
    } catch (error) {
        console.error("Hiba a kérdések lekérésekor:", error);
    }
}

const getSubjects = async () => {
    try {
        const res = await api.get("/api/subjects");
        subjects.value = res.data;
    } catch (error) {
        console.error("Hiba a tantárgyak lekérésekor:", error);
    }
}


const deleteQuestion = async (id) => {
    try {
        await api.delete(`/api/questions/${id}`);
        toast.success("Kérdés sikeresen törölve!"); S
        getQuestions();
    } catch (error) {
        console.error("Törlési hiba:", error);
        toast.error("Nem sikerült törölni a kérdést.");
    }
}

onBeforeMount(() => {
    getQuestions();
    getSubjects();
    getReports();
});
</script>

<template>
    <ProtectedPageContainer class="relative overflow-hidden">
        <i
            class="fa-solid fa-layer-group rotate-90 text-accentPurple text-[1500px] absolute z-0 opacity-5 top-200 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
        <Navbar minimal />

        <section class="relative z-10 mt-24 max-w-6xl mx-auto px-6 text-textWhite">
            <div class="flex gap-4 mb-12">
                <button @click="activeTab = 'questions'"
                    :class="[activeTab == 'questions' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white/5 text-white/50 hover:bg-white/10']"
                    class="px-6 py-3 rounded-xl font-bold uppercase tracking-wider transition-all duration-300">
                    <i class="fa-solid fa-circle-question mr-2"></i> Kérdéskezelés
                </button>

                <button @click="activeTab = 'reports'"
                    :class="[activeTab == 'reports' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white/5 text-white/50 hover:bg-white/10']"
                    class="px-6 py-3 rounded-xl font-bold uppercase tracking-wider transition-all duration-300">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Játékos Reportok
                </button>
            </div>

            <div v-if="activeTab == 'questions'" class="fade-in">
                <QuestionAdministration :initialQuestions="questions" :availableSubjects="subjects"
                    @refresh="getQuestions" @deleteQuestion="deleteQuestion" />
            </div>

            <div v-else-if="activeTab == 'reports'" class="fade-in">
                <QuestionReportAdministration :reports="reports" @refresh="getReports" @deleteReport="deleteReport" />
            </div>

            <div v-else class="fade-in text-center py-20">
                <p class="text-white/20 uppercase tracking-widest">Nincs kezelendő report.</p>
            </div>
        </section>
    </ProtectedPageContainer>
</template>

<style scoped>
.fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>