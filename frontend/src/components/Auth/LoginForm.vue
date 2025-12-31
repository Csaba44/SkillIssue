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
  // Input verification
  if (formData.value.email.trim() == "" || formData.value.password.trim() == "") return toast.error("Minden mező kitöltése kötelező.", { duration: 3000 });

  const loginPromise = async () => {
    await api.get("/api/csrf-cookie");
    const res = await api.post("/api/login", formData.value);
    await userStore.verifySession();

    if (userStore.isAuthenticated) {
      return "Sikeres bejelentkezés";
    }

    throw new Error("Sikertelen bejelentkezés");
  };

  toast.promise(loginPromise(), {
    loading: "Bejelentkezés folyamatban...",
    success: (msg) => {
      router.push("/");
      return msg;
    },
    error: (error) => {
      if (!error.response) {
        return "Ellenőrizd az internetkapcsolatot.";
      }

      const { status, data } = error.response;

      if (status === 401) return data.message;
      if (status !== 422) return "Ismeretlen hiba történt.";

      const errors = data.errors;
      const firstKey = Object.keys(errors)[0];
      return errors[firstKey][0];
    },
  });
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
