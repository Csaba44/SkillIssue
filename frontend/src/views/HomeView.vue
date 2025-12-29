<script setup>
import router from "../config/router";
import { useUserStore } from "../stores/UserStore";
import Navbar from "../components/HomePage/Navbar.vue";
import Button from "../components/Generic/Button.vue";

const userStore = useUserStore();
</script>

<template>
  <Navbar></Navbar>
  <div class="pt-2 px-5 md:px-10">
    <Button @click="console.log('btn clicked')" class="text-sm" title="Vágjunk bele" />

    <div>
      {{ userStore.isAuthenticated ? `Hello, ${userStore.user.name}` : "Hello, guest" }}
      <button v-if="userStore.isAuthenticated" @click="userStore.logout()">Logout</button>
      <button v-else="userStore.isAuthenticated" @click="router.push('/login')">Login</button>
    </div>
  </div>
</template>
