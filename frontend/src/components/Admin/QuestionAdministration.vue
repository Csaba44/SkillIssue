<script setup>
import { ref, computed } from "vue";
import Question from "./Question.vue";
import QuestionModal from "./QuestionModal.vue";

const props = defineProps({
  initialQuestions: Array,
  availableSubjects: Array,
});
const emit = defineEmits(["refresh", "deleteQuestion"]);

const isModalOpen = ref(false);
const selectedQuestion = ref(null);
const searchQuery = ref("");
const selectedSubjectFilter = ref(null);

const activeSubjects = computed(() => {
  const subjectsInUse = props.initialQuestions.filter((q) => q.subject).map((q) => q.subject);
  return Array.from(new Map(subjectsInUse.map((s) => [s.id, s])).values());
});

const filteredQuestions = computed(() => {
  return props.initialQuestions.filter((q) => {
    const matchesSearch = !searchQuery.value || q.question.toLowerCase().includes(searchQuery.value.toLowerCase());

    const matchesSubject = !selectedSubjectFilter.value || q.subject_id === selectedSubjectFilter.value;

    return matchesSearch && matchesSubject;
  });
});

const openEditModal = (q) => {
  selectedQuestion.value = q;
  isModalOpen.value = true;
};

const openCreateModal = () => {
  selectedQuestion.value = null;
  isModalOpen.value = true;
};
</script>

<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
      <div class="relative w-full lg:w-96">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/20"></i>
        <input v-model="searchQuery" type="text" placeholder="Keress a kérdések között..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-white outline-none focus:border-accentGreen transition-all" />
      </div>

      <button @click="openCreateModal" class="bg-accentGreen text-black px-8 py-3 rounded-full font-extrabold hover:scale-105 transition-all shadow-lg shadow-accentGreen/20"><i class="fa-solid fa-plus mr-2"></i> ÚJ KÉRDÉS</button>
    </div>

    <div class="flex flex-wrap gap-2">
      <button @click="selectedSubjectFilter = null" :class="selectedSubjectFilter === null ? 'bg-white text-black' : 'bg-white/5 text-white/60 hover:bg-white/10'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Összes ({{ initialQuestions.length }})</button>

      <button v-for="subject in activeSubjects" :key="subject.id" @click="selectedSubjectFilter = subject.id" :class="selectedSubjectFilter === subject.id ? 'bg-accentGreen text-black' : 'bg-white/5 text-white/60 hover:bg-white/10'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
        {{ subject.name }}
      </button>
    </div>

    <div class="grid gap-4">
      <div v-if="filteredQuestions.length > 0" class="grid gap-3">
        <Question v-for="(q, index) in filteredQuestions" :key="q.id" :questionData="q" :id="q.id" @delete="(id) => emit('deleteQuestion', id)" @edit="openEditModal(q)" />
      </div>

      <div v-else class="py-20 text-center border border-dashed border-white/5 rounded-3xl bg-white/1">
        <i class="fa-solid fa-ghost text-4xl text-white/10 mb-4 block"></i>
        <p class="text-white/20 font-medium">Nincs ilyen kérdésünk...</p>
      </div>
    </div>

    <QuestionModal v-if="isModalOpen" :availableSubjects="availableSubjects" :editData="selectedQuestion" @close="isModalOpen = false" @refresh="emit('refresh')" />
  </div>
</template>
