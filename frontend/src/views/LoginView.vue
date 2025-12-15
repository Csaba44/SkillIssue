<script setup>
import { onBeforeMount, ref } from "vue";
import api from "../config/api";
import { useUserStore } from "../stores/UserStore";
import router from "../config/router";

const userStore = useUserStore();

const formData = ref({
  email: "teszt.elek@gmail.com",
  password: "password123",
});

const loginSubmit = async () => {
  await api.get("/api/csrf-cookie");
  const response = await api.post("/api/login", formData.value);
  console.log(response);
  await userStore.verifySession();
  if (userStore.isAuthenticated) router.push("/");
};

onBeforeMount(async () => {
  await userStore.verifySession();
  if (userStore.isAuthenticated) router.push("/");
});
</script>

<template>
  <form v-if="!userStore.isAuthenticated" @submit.prevent="loginSubmit">
    <input type="email" name="email" v-model="formData.email" />
    <input type="password" name="password" v-model="formData.password" />
    <button type="submit">Login</button>
  </form>
</template>
