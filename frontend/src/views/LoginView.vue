<script setup>
import { onBeforeMount, ref } from "vue";
import api from "../config/api";
import { useUserStore } from "../stores/UserStore";
import router from "../config/router";
import Input from "../components/Generic/Input.vue";
import Button from "../components/Generic/Button.vue";

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
  <div class="bg-bgDark w-screen h-screen p-8">
    <div class="bg-bgAlternate w-full h-full rounded-md border border-white/25 grid grid-rows-3 grid-cols-1 md:grid-rows-1 md:grid-cols-2">
      <div class="items-center justify-center border-b md:border-r border-white/25 relative">
        <div class="absolute inset-0 h-full w-full opacity-20 bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-size-[16px_16px] z-0"></div>
        <div class="z-10 grid grid-cols-1 grid-rows-5 p-10 h-full">
          <div class="row-span-5 md:row-span-2 z-10 text-2xl md:text-5xl font-bold flex flex-col text-center gap-5 justify-center items-center">
            <span> <span class="text-white">Skill</span><span class="text-primary">Issue</span> </span>
            <span class="text-2xl text-textWhite">Játssz, tanulj és készülj az érettségire!</span>
          </div>

          <div class="hidden md:visible row-span-3 md:grid grid-rows-3 grid-cols-3 gap-5 z-10 items-center text-center">
            <div class="bg-accentYellow w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
            <div class="bg-accentYellow w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
            <div class="bg-accentYellow w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>

            <div class="bg-accentGreen w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
            <div class="bg-accentGreen w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
            <div class="bg-accentGreen w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>

            <div class="bg-accentPurple w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
            <div class="bg-accentPurple w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
            <div class="bg-accentPurple w-full h-full flex justify-center items-center rounded-lg">Államalapítás</div>
          </div>
        </div>
      </div>
      <div class="row-span-2 md:row-span-1 grid grid-cols-1 grid-rows-5 p-10 h-full items-center justify-center">
        <div class="row-span-2 text-2xl md:text-5xl font-bold text-textWhite flex flex-col text-center gap-5 justify-center">Bejelentkezés</div>

        <form class="row-span-3 py-5 flex flex-col gap-3 items-center justify-start h-full w-full">
          <Input name="email" autocomplete="email" placeholder="gipsz.jakab@gmail.com" icon="fa-solid fa-at" class="w-full! lg:w-[50%]!" />
          <Input name="password" autocomplete="password" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
          <Button title="Bejelentkezés" class="mt-3"></Button>
        </form>
      </div>
    </div>
  </div>

  <!-- <form v-if="!userStore.isAuthenticated" @submit.prevent="loginSubmit">
    <input type="email" name="email" v-model="formData.email" />
    <input type="password" name="password" v-model="formData.password" />
    <button type="submit">Login</button>
  </form>-->
</template>
