<script setup>
import { onBeforeMount, ref, computed } from 'vue';
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import api from "../config/api";

const activeTab = ref('questions');
const questions = ref([]);
const subjects = ref([]);
const selectedSubject = ref('all');

const getQuestions = async () => {
    try {
        const res = await api.get("/api/questions");
        questions.value = res.data;

        subjects.value = [];
        res.data.forEach(q => {
            const subjectName = q.subject.name;

            if (!subjects.value.includes(subjectName)) {
                subjects.value.push(subjectName);
            }
        });

        subjects.value.sort();

    } catch (error) {
        console.error("Hiba:", error);
    }
}

const filteredQuestions = computed(() => {
    if (selectedSubject.value == 'all') return questions.value;
    return questions.value.filter(q => q.subject?.name == selectedSubject.value);
});

onBeforeMount(getQuestions);
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
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-4xl font-bold tracking-tight uppercase">
                            Admin <span class="text-primary not-italic">Kérdések</span>
                        </h2>
                        <p class="text-white/30 text-sm font-medium tracking-wide">
                            {{ filteredQuestions.length }} kérdés összesen
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button @click="selectedSubject = 'all'"
                            :class="selectedSubject == 'all' ? 'bg-primary border-primary text-white' : 'border-white/10 text-white/40'"
                            class="px-4 py-1.5 border rounded-full text-[11px] font-bold uppercase tracking-widest transition-all">
                            Összes
                        </button>

                        <button v-for="subject in subjects" :key="subject" @click="selectedSubject = subject"
                            :class="selectedSubject == subject ? 'bg-primary border-primary text-white' : 'border-white/10 text-white/40'"
                            class="px-4 py-1.5 border rounded-full text-[11px] font-bold uppercase tracking-widest transition-all">
                            {{ subject }}
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-8">
                    <div v-for="q,index in filteredQuestions" :key="q.id"
                        class="bg-white/[0.03] border border-white/5 p-5 rounded-xl flex justify-between items-center group hover:bg-white/5 transition-all">
                        <div class="flex items-center gap-4">
                            <span class="text-white/10 font-mono text-xs">#{{index+1}}</span>
                            <h3 class="text-md font-medium text-white/80 group-hover:text-white transition-colors">
                                {{ q.question }}
                            </h3>
                        </div>

                        <div class="flex gap-2">
                            <button
                                class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-white/10 transition-all text-white/40 hover:text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button
                                class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-red-500/20 transition-all text-white/40 hover:text-red-500">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div v-if="filteredQuestions.length == 0"
                        class="py-20 text-center text-white/10 uppercase tracking-widest text-sm">
                        Nincs találat.
                    </div>
                </div>
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