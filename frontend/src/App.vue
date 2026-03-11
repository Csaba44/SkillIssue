<script setup>
import { onBeforeMount, onMounted } from "vue";
import { useUserStore } from "./stores/UserStore";
import { Toaster } from "vue-sonner";
import "vue-sonner/style.css";

const userStore = useUserStore();

onBeforeMount(async () => {
  await userStore.verifySession();
});
</script>

<template>
  <main>
    <RouterView />
  </main>
  <Toaster rich-colors position="bottom-left" :closeButton="true" closeButtonPosition="top-right" theme="light"></Toaster>
</template>
