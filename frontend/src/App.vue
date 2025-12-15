<script setup>
import { useUserStore } from "./stores/UserStore";
const userStore = useUserStore();
userStore.verifySession();
</script>

<template>
  <main>
    <RouterView />
  </main>
</template>
