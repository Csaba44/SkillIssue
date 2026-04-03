<script setup>
import { onBeforeMount, ref } from "vue";
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import QuestionAdministration from "../components/Admin/QuestionAdministration.vue";
import QuestionReportAdministration from "../components/Admin/QuestionReportAdministration.vue";
import UserReportAdministration from "../components/Admin/UserReportAdministration.vue"; // Az új komponens
import { toast } from "vue-sonner";
import api from "../config/api";
import BanAdministration from "../components/Admin/BanAdministration.vue";

const activeTab = ref("questions");
const questions = ref([]);
const subjects = ref([]);
const questionReports = ref([]);
const userReports = ref([]);

const getQuestionReports = async () => {
  try {
    const res = await api.get("/api/question-reports");
    questionReports.value = res.data;
  } catch (error) {
    console.error("Hiba a kérdés reportok lekérésekor:", error);
  }
};

const deleteQuestionReport = async (id) => {
  try {
    await api.delete(`/api/question-reports/${id}`);
    toast.success("Kérdés jelentés törölve!");
    getQuestionReports();
  } catch (error) {
    toast.error("Hiba a törlés során.");
  }
};

const getUserReports = async () => {
  try {
    const res = await api.get("/api/user-reports");
    userReports.value = res.data;
  } catch (error) {
    console.error("Hiba a játékos reportok lekérésekor:", error);
  }
};

const deleteUserReport = async (id) => {
  try {
    await api.delete(`/api/user-reports/${id}`);
    toast.success("Játékos jelentés törölve!");
    getUserReports();
  } catch (error) {
    toast.error("Hiba a törlés során.");
  }
};

const getQuestions = async () => {
  try {
    const res = await api.get("/api/questions");
    questions.value = res.data;
    getSubjects();
  } catch (error) {
    console.error("Hiba a kérdések lekérésekor:", error);
  }
};

const getSubjects = async () => {
  try {
    const res = await api.get("/api/subjects");
    subjects.value = res.data;
  } catch (error) {
    console.error("Hiba a tantárgyak lekérésekor:", error);
  }
};

const deleteQuestion = async (id) => {
  try {
    await api.delete(`/api/questions/${id}`);
    toast.success("Kérdés sikeresen törölve!");
    getQuestions();
  } catch (error) {
    toast.error("Nem sikerült törölni a kérdést.");
  }
};

onBeforeMount(() => {
  getQuestions();
  getSubjects();
  getQuestionReports();
  getUserReports();
});
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i class="fa-solid fa-layer-group rotate-90 text-accentPurple text-[1500px] absolute z-0 opacity-5 top-200 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
    <Navbar minimal />

    <section class="relative z-10 mt-24 max-w-6xl mx-auto md:px-6 text-textWhite">
      <div class="flex flex-wrap gap-4 mb-12">
        <button @click="activeTab = 'questions'" :class="[activeTab == 'questions' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white/5 text-white/50 hover:bg-white/10']" class="px-6 py-3 rounded-xl font-bold uppercase tracking-wider transition-all duration-300 text-xs"><i class="fa-solid fa-circle-question mr-2"></i> Kérdéskezelés</button>

        <button @click="activeTab = 'q-reports'" :class="[activeTab == 'q-reports' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white/5 text-textWhite/50 hover:bg-white/10']" class="px-6 py-3 rounded-xl font-bold uppercase tracking-wider transition-all duration-300 text-xs"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Kérdés Reportok</button>

        <button @click="activeTab = 'u-reports'" :class="[activeTab == 'u-reports' ? 'bg-amber-500 text-black shadow-lg shadow-amber-500/20' : 'bg-white/5 text-white/50 hover:bg-white/10']" class="px-6 py-3 rounded-xl font-bold uppercase tracking-wider transition-all duration-300 text-xs"><i class="fa-solid fa-user-shield mr-2"></i> Játékos Reportok</button>

        <button @click="activeTab = 'bans'" :class="[activeTab == 'bans' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white/5 text-white/50 hover:bg-white/10']" class="px-6 py-3 rounded-xl font-bold uppercase tracking-wider transition-all duration-300 text-xs"><i class="fa-solid fa-ban mr-2"></i> Banok</button>
      </div>

      <div v-if="activeTab == 'questions'" class="fade-in">
        <QuestionAdministration :initialQuestions="questions" :availableSubjects="subjects" @refresh="getQuestions" @deleteQuestion="deleteQuestion" />
      </div>

      <div v-else-if="activeTab == 'q-reports'" class="fade-in">
        <QuestionReportAdministration :reports="questionReports" @refresh="getQuestionReports" @deleteReport="deleteQuestionReport" />
      </div>

      <div v-else-if="activeTab == 'u-reports'" class="fade-in">
        <UserReportAdministration :reports="userReports" @refresh="getUserReports" @deleteReport="deleteUserReport" />
      </div>

      <div v-else-if="activeTab == 'bans'" class="fade-in">
        <BanAdministration />
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
