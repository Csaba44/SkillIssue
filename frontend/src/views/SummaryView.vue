<script setup>
import { useRoute } from "vue-router";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import RankedSummary from "../components/Game/RankedSummary.vue";
import PracticeSummary from "../components/Game/PracticeSummary.vue";

const route = useRoute();
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>

    <RankedSummary v-if="route.name == 'summaryRanked' && route.params.matchUuid" :uuid="route.params.matchUuid" />
    <PracticeSummary v-if="route.name == 'summaryPractice' && route.params.id" :id="route.params.id" />
  </ProtectedPageContainer>
</template>
