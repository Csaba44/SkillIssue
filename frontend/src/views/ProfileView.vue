<script setup>
import { onBeforeMount, ref } from "vue";
import { useRoute } from "vue-router";
import api from "../config/api";
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
import { useUserStore } from "../stores/UserStore";
import { storeToRefs } from "pinia";

const route = useRoute();
const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const profile = ref(null);

const getUser = async () => {
    try {
        const res = await api.get(`/api/profiles/${route.params.id}`);
        if (res.status != 200) {
            return console.log("error", res);
        };
        profile.value = res.data;
        console.log(profile.value);
    } catch (error) {
        console.log(error);
    }
}

onBeforeMount(getUser);
</script>

<template>
  <ProtectedPageContainer class="relative overflow-hidden">
    <i class="pointer-events-none fa-solid fa-graduation-cap rotate-30 text-accentPurple text-[2190px] absolute z-0 opacity-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
    <Navbar minimal />

    

  </ProtectedPageContainer>
</template>

<style scoped>

</style>