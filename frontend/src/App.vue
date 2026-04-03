<script setup>
import { onBeforeMount, onMounted } from "vue";
import { useUserStore } from "./stores/UserStore";
import { Toaster } from "vue-sonner";
import "vue-sonner/style.css";
import { useGameStore } from "./stores/GameStore";
import { socket } from "./config/websocket";
import { useRoute } from "vue-router";
import router from "./config/router";
const userStore = useUserStore();

const gameStore = useGameStore();

const route = useRoute();

const connectWebsocket = () => {
  socket.connect();
  socket.on("connect", () => console.log("[SOCKET] connected."));
  socket.on("disconnect", () => console.log("[SOCKET] disconnected."));
  gameStore.initListeners();
};

onBeforeMount(async () => {
  await userStore.verifySession();

  if (route.name !== "questionReport" && route.name !== "userReport") {
    connectWebsocket();
  }
});

router.afterEach((to, from) => {
  const reportRoutes = ["questionReport", "userReport"];

  if (reportRoutes.includes(from.name) && !reportRoutes.includes(to.name)) {
    connectWebsocket();
  }
});
</script>

<template>
  <main>
    <RouterView />
  </main>
  <Toaster rich-colors position="bottom-left" :closeButton="true" closeButtonPosition="top-right" theme="light"></Toaster>
</template>
