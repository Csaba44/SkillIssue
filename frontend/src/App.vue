<script setup>
import { onBeforeMount, onMounted } from "vue";
import { useUserStore } from "./stores/UserStore";
import { Toaster } from "vue-sonner";
import "vue-sonner/style.css";
import { useGameStore } from "./stores/GameStore";
const userStore = useUserStore();

const gameStore = useGameStore();

onBeforeMount(async () => {
  await userStore.verifySession();
});

onMounted(() => {
  gameStore.initListeners();
});
</script>

<template>
  <main>
    <RouterView />
  </main>
  <Toaster rich-colors position="bottom-left" :closeButton="true" closeButtonPosition="top-right" theme="light"></Toaster>
</template>
