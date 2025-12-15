<script setup>
import { onBeforeMount, ref } from "vue";
import { useUserStore } from "../stores/UserStore";
import api from "../config/api";
const userStore = useUserStore();

onBeforeMount(async () => {
  await userStore.verifySession();
  if (userStore.isAuthenticated) router.push("/");
});

const formData = ref({
  name: "Teszt Kovács Elek",
  email: "teszt.elek32@gmail.com",
  password: "password123",
});

const message = ref("");

const registerSubmit = async () => {
  try {
    await api.get("/api/csrf-cookie");
    const response = await api.post("/api/register", formData.value);
    if (response.status == 201) {
      message.value = response.data.message;
    }
  } catch (error) {
    if (!error.response) return console.error("Check internet connection");
    console.error(error);
  }
};
</script>

<template>
  <form v-if="!userStore.isAuthenticated" @submit.prevent="registerSubmit">
    {{ message }}
    <input type="text" name="name" v-model="formData.name" />
    <input type="email" name="email" v-model="formData.email" />
    <input type="password" name="password" v-model="formData.password" />
    <button type="submit">Register</button>
  </form>
</template>
