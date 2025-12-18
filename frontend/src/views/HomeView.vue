<script setup>
import router from "../config/router";
import { useUserStore } from "../stores/UserStore";
import Navbar from "../components/HomePage/Navbar.vue";
import CustomButton from "../components/Generic/CustomButton.vue";


const userStore = useUserStore();
</script>

<template>
  <Navbar></Navbar>
  <CustomButton title="Vágjunk bele"></CustomButton> <br>
  {{ userStore.isAuthenticated ? `Hello, ${userStore.user.name}` : "Hello, guest" }}
  <button v-if="userStore.isAuthenticated" @click="userStore.logout()">Logout</button>
  <button v-else="userStore.isAuthenticated" @click="router.push('/login')">Login</button>
</template>
