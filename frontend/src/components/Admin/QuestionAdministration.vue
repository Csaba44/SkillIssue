<script setup>
import { ref, computed, watchEffect } from 'vue';
import Question from "./Question.vue";

const props = defineProps({
    initialQuestions: Array
});

const selectedSubject = ref('all');
const subjects = ref([]);

watchEffect(() => {
    subjects.value = [];
    props.initialQuestions.forEach(q => {
        const name = q.subject?.name;
        if (name && !subjects.value.includes(name)) {
            subjects.value.push(name);
        }
    });
    subjects.value.sort();
});

const filteredQuestions = computed(() => {
    if (selectedSubject.value == 'all') return props.initialQuestions;
    return props.initialQuestions.filter(q => q.subject?.name == selectedSubject.value);
});
</script>

<template>
    <div>
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
            <Question 
                v-for="(q, index) in filteredQuestions" 
                :key="q.id || index" 
                :questionData="q" 
                :index="index" 
            />

            <div v-if="filteredQuestions.length == 0"
                class="py-20 text-center text-white/10 uppercase tracking-widest text-sm border border-white/5 rounded-xl">
                Nincs találat.
            </div>
        </div>
    </div>
</template>