<script setup>
import { useUserStore } from "./stores/UserStore";
const userStore = useUserStore();
userStore.verifySession();
import { Toaster } from "vue-sonner";
import "vue-sonner/style.css";
</script>

<template>
  <main>
    <RouterView />
  </main>
  <Toaster rich-colors position="bottom-left" :closeButton="true" closeButtonPosition="top-right" theme="light" ></Toaster>
</template>
