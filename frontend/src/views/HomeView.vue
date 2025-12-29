<script setup>
import router from "../config/router";
import { useUserStore } from "../stores/UserStore";
import Navbar from "../components/HomePage/Navbar.vue";
import Button from "../components/Generic/Button.vue";
import Header from "../components/HomePage/Header.vue";

const userStore = useUserStore();
</script>

<template>
  <Navbar></Navbar>
  <Header></Header>

  <!--
  <div>
    <Button @click="console.log('btn clicked')" class="text-sm" title="Vágjunk bele" />
    {{ userStore.isAuthenticated ? `Hello, ${userStore.user.name}` : "Hello, guest" }}
    <button v-if="userStore.isAuthenticated" @click="userStore.logout()">Logout</button>
    <button v-else="userStore.isAuthenticated" @click="router.push('/login')">Login</button>
  </div>
  -->
</template>
