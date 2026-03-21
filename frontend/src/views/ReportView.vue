<script setup>
import { computed, ref } from "vue";
import router from "../config/router";
import QuestionReport from "../components/Report/QuestionReport.vue";
import { useRoute } from "vue-router";

const reportType = computed(() => (router.currentRoute.value.name == "questionReport" ? "question" : "user"));

const route = useRoute();

const questionToken = route.query.questiontoken;
const question = route.query.question;
const answers = JSON.parse(decodeURIComponent(route.query.answers));
</script>

<template>
  <QuestionReport v-if="reportType === 'question' && questionToken && question" :questionToken="questionToken" :question="question" :answers="answers" />
</template>
