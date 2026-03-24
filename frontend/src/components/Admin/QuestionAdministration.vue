<script setup>
import { ref, computed, watchEffect } from 'vue';
import Question from "./Question.vue";
import NewQuestionModal from "./NewQuestionModal.vue";

const props = defineProps({ initialQuestions: Array });
const emit = defineEmits(['refresh','deleteQuestion']);

const selectedSubject = ref('all');
const subjectsList = ref([]); 
const isModalOpen = ref(false);

watchEffect(() => {
    const unique = {};
    props.initialQuestions.forEach(q => {
        if (q.subject && !unique[q.subject.id]) {
            unique[q.subject.id] = q.subject.name;
        }
    });
    subjectsList.value = Object.entries(unique).map(([id, name]) => ({ id: parseInt(id), name }));
});

const filteredQuestions = computed(() => {
    if (selectedSubject.value == 'all') return props.initialQuestions;
    return props.initialQuestions.filter(q => q.subject?.name == selectedSubject.value);
});
</script>

<template>
    <div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8">
            <div class="flex flex-col gap-4">
                <button @click="isModalOpen = true"
                    class="bg-accentGreen text-black px-6 py-3 rounded-xl font-black uppercase tracking-tighter flex items-center gap-2 hover:brightness-110 transition-all shadow-lg shadow-accentGreen/20">
                    <i class="fa-solid fa-plus"></i> Új kérdés hozzáadása
                </button>
                <p class="text-white/30 text-sm font-medium tracking-wide">{{ filteredQuestions.length }} kérdés találva
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button @click="selectedSubject = 'all'"
                    :class="selectedSubject == 'all' ? 'bg-primary border-primary text-white' : 'border-white/10 text-white/40'"
                    class="px-4 py-1.5 border rounded-full text-[11px] font-bold uppercase tracking-widest transition-all">
                    Összes
                </button>
                <button v-for="s in subjectsList" :key="s.id" @click="selectedSubject = s.name"
                    :class="selectedSubject == s.name ? 'bg-primary border-primary text-white' : 'border-white/10 text-white/40'"
                    class="px-4 py-1.5 border rounded-full text-[11px] font-bold uppercase tracking-widest transition-all">
                    {{ s.name }}
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-3 mt-8">
            <Question v-for="(q, index) in filteredQuestions" :key="q.id || index" :questionData="q" :index="index" @delete="(id) => emit('deleteQuestion', id)"/>
            <div v-if="filteredQuestions.length == 0"
                class="py-20 text-center text-white/10 uppercase tracking-widest text-sm border border-white/5 rounded-xl">
                Nincs találat.
            </div>
        </div>

        <NewQuestionModal v-if="isModalOpen" :availableSubjects="subjectsList" @close="isModalOpen = false"
            @refresh="emit('refresh')" />
    </div>
</template>