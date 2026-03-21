<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import router from "../config/router";
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import QuestionReport from "../components/Report/QuestionReport.vue";

const reportType = computed(() => (router.currentRoute.value.name == "questionReport" ? "question" : "user"));

const route = useRoute();
const questionToken = route.query.questiontoken;
const question = route.query.question;
const answers = JSON.parse(decodeURIComponent(route.query.answers));
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i class="pointer-events-none fa-solid fa-flag text-error text-[1200px] absolute z-0 opacity-5 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rotate-12"></i>
    <Navbar minimal />

    <QuestionReport v-if="reportType === 'question' && questionToken && question" :questionToken="questionToken" :question="question" :answers="answers" />
  </ProtectedPageContainer>
</template>
