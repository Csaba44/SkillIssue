<script setup>
import { onBeforeMount, ref } from 'vue'; 
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import api from "../config/api";

const activeTab = ref('questions');
const questions = ref([]);

const getQuestions = async () => {
    try {
        const res = await api.get("/api/questions");
        questions.value = res.data;
    } catch (error) {
        console.log(error);
    }
}

onBeforeMount(getQuestions);
</script>

<template>
    <ProtectedPageContainer class="relative overflow-hidden">
        
        <i class="fa-solid fa-layer-group rotate-90 text-accentPurple text-[1500px] absolute z-0 opacity-5 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
        
        <Navbar minimal />

        <section class="relative z-10 mt-24 max-w-6xl mx-auto px-6 text-textWhite">
            
            <div class="flex gap-4 mb-12">
                <button 
                    @click="activeTab = 'questions'"
                    :class="[activeTab === 'questions' ? 'bg-primary text-white' : 'bg-white/5 text-white/50 hover:bg-white/10']"
                    class="px-6 py-3 rounded-lg font-bold uppercase tracking-wider transition-all duration-200"
                >
                    <i class="fa-solid fa-circle-question mr-2"></i> Kérdéskezelés
                </button>

                <button 
                    @click="activeTab = 'reports'"
                    :class="[activeTab === 'reports' ? 'bg-red-500 text-white' : 'bg-white/5 text-white/50 hover:bg-white/10']"
                    class="px-6 py-3 rounded-lg font-bold uppercase tracking-wider transition-all duration-200"
                >
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Játékos Reportok
                </button>
            </div>

            <div v-if="activeTab === 'questions'" class="fade-in">
                <div class="flex flex-col gap-2 mb-8">
                    <h2 class="text-4xl font-bold tracking-tight uppercase">
                        Admin <span class="text-primary not-italic">Kérdések</span>
                    </h2>
                    <p class="text-white/30 text-sm font-medium tracking-wide">
                        Vezérlőpult a rendszer kérdéseinek kezeléséhez
                    </p>
                </div>
                <div class="mt-8 p-12 border-2 border-dashed border-white/10 rounded-xl text-center text-white/20">
                    Kérdések listája helye...
                </div>
            </div>

            <div v-else-if="activeTab === 'reports'" class="fade-in">
                <div class="flex flex-col gap-2 mb-8">
                    <h2 class="text-4xl font-bold tracking-tight uppercase">
                        Játékos <span class="text-red-500 not-italic">Reportok</span>
                    </h2>
                    <p class="text-white/30 text-sm font-medium tracking-wide">
                        Beérkezett panaszok és jelentések kezelése
                    </p>
                </div>
                <div class="mt-8 p-12 border-2 border-dashed border-red-500/20 rounded-xl text-center text-white/20">
                    Reportok listája helye...
                </div>
            </div>

        </section>

    </ProtectedPageContainer>
</template>

<style scoped>
.fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>