<script setup>
import router from "../config/router";
import { useUserStore } from "../stores/UserStore";

const userStore = useUserStore();
</script>

<template>
  {{ userStore.isAuthenticated ? `Hello, ${userStore.user.name}` : "Hello, guest" }}
  <button v-if="userStore.isAuthenticated" @click="userStore.logout()">Logout</button>
  <button v-else="userStore.isAuthenticated" @click="router.push('/login')">Login</button>
</template>
