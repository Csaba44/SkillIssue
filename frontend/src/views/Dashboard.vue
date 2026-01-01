<script setup>
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import { useUserStore } from "../stores/UserStore";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const { isAuthenticated } = storeToRefs(userStore);
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i class="fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2180px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
    <Navbar />
  </ProtectedPageContainer>
</template>
