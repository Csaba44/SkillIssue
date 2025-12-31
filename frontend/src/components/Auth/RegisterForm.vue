<script setup>
import { ref } from "vue";
import Button from "../Generic/Button.vue";
import Checkbox from "../Generic/Checkbox.vue";
import Input from "../Generic/Input.vue";
import { toast } from "vue-sonner";
import api from "../../config/api";

const emit = defineEmits(["switch-form"]);

const formData = ref({
  name: "",
  email: "",
  password: "",
  password2: "",
  accepted: false,
});

const registerSubmit = async () => {
  const registerPromise = async () => {
    await api.get("/api/csrf-cookie");
    const res = await api.post("/api/register", { name: formData.value.name, email: formData.value.email, password: formData.value.password });

    if (res.status === 201) return "Sikeres regisztráció";

    throw new Error("Sikertelen regisztráció");
  };

  toast.promise(registerPromise(), {
    loading: "Regisztráció folyamatban...",
    success: (msg) => msg,
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
  <form @submit.prevent="registerSubmit" class="row-span-5 lg:row-span-4 py-5 flex flex-col gap-3 items-center justify-center h-full w-full">
    <Input v-model="formData.name" name="text" autocomplete="name" placeholder="Gipsz Jakab" icon="fa-solid fa-user" class="w-full! lg:w-[50%]!" />
    <Input v-model="formData.email" name="email" autocomplete="email" placeholder="gipsz.jakab@gmail.com" icon="fa-solid fa-at" class="w-full! lg:w-[50%]!" />
    <Input v-model="formData.password" name="password" autocomplete="off" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
    <Input v-model="formData.password2" name="password2" autocomplete="off" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
    <div class="lg:max-w-105">
      <Checkbox
        v-model="formData.accepted"
        @state-change="
          (isChecked) => {
            console.log(isChecked.value);
          }
        "
        ><span class="text-textWhite text-xs">Kijelentem, hogy az <a class="text-accentYellow cursor-pointer hover:underline">adatkezelési tájékoztatót</a> elolvastam, és elfogadom.</span></Checkbox
      >
    </div>
    <Button title="Regisztráció" class="mt-3"></Button>
    <a class="text-accentYellow cursor-pointer hover:underline" @click="emit('switch-form')">Már van fiókja?</a>
  </form>
</template>
