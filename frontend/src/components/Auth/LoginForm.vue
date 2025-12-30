<script setup>
import { ref } from "vue";
import Button from "../Generic/Button.vue";
import Input from "../Generic/Input.vue";
import api from "../../config/api";
import { useUserStore } from "../../stores/UserStore";
import router from "../../config/router";
import { toast } from "vue-sonner";

const userStore = useUserStore();

const emit = defineEmits(["switch-form"]);

const formData = ref({
  email: "",
  password: "",
});

const loginSubmit = async () => {
  try {
    await api.get("/api/csrf-cookie");
    const response = await api.post("/api/login", formData.value);
    console.log(response);
    await userStore.verifySession();
    if (userStore.isAuthenticated) router.push("/");
  } catch (error) {
    if (!error.response) {
      return toast.error("Kérem ellenőrizze az internetkapcsolatát.", { duration: 3000 });
    } else if (error.response.status === 401) {
      return toast.error(error.response.data.message, { duration: 3000 });
    } else if (error.response.status !== 422) {
      return toast.error("Ismeretlen hiba történt.", { duration: 3000 });
    }

    // 402 status
    for (const key in error.response.data.errors) {
      const contentError = error.response.data.errors[key];
      contentError.forEach((err) => {
        toast.error(err, { duration: 6000 });
      });
    }
  }
};
</script>

<template>
  <form class="row-span-5 lg:row-span-4 py-5 flex flex-col gap-3 items-center justify-center h-full w-full" @submit.prevent="loginSubmit">
    <Input v-model="formData.email" name="email" autocomplete="email" placeholder="gipsz.jakab@gmail.com" icon="fa-solid fa-at" class="w-full! lg:w-[50%]!" />
    <Input v-model="formData.password" name="password" autocomplete="password" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
    <Button title="Bejelentkezés" class="mt-3"></Button>
    <a class="text-accentYellow cursor-pointer hover:underline" @click="emit('switch-form')">Nincs még fiókja?</a>
  </form>
</template>
