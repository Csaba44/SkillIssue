<script setup>
import { onBeforeMount, ref } from 'vue';
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import QuestionAdministration from "../components/Admin/QuestionAdministration.vue";
import api from "../config/api";

const activeTab = ref('questions');
const questions = ref([]);

const getQuestions = async () => {
    try {
        const res = await api.get("/api/questions");
        questions.value = res.data;
    } catch (error) {
        console.error("Hiba a kérdések lekérésekor:", error);
    }
}

onBeforeMount(getQuestions);
</script>

<template>
    <ProtectedPageContainer class="relative overflow-hidden">
        <i class="fa-solid fa-layer-group rotate-90 text-accentPurple text-[1500px] absolute z-0 opacity-5 top-200 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
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
                <QuestionAdministration :initialQuestions="questions" />
            </div>

            <div v-else class="fade-in text-center py-20">
                <p class="text-white/20 uppercase tracking-widest">Nincs kezelendő report (Hamarosan...).</p>
            </div>
        </section>
    </ProtectedPageContainer>
</template>