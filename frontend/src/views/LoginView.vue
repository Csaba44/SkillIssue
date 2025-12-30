<script setup>
import { onBeforeMount, ref } from "vue";
import api from "../config/api";
import { useUserStore } from "../stores/UserStore";
import router from "../config/router";
import Input from "../components/Generic/Input.vue";
import Button from "../components/Generic/Button.vue";
import { historyCards, literatureCards, grammarCards } from "../utils/TopicCardList";
import TopicCard from "../components/Generic/TopicCard.vue";
import LoginForm from "../components/Auth/LoginForm.vue";
import RegisterForm from "../components/Auth/RegisterForm.vue";
import { useRoute } from "vue-router";

const userStore = useUserStore();
const route = useRoute();

const formData = ref({
  email: "teszt.elek@gmail.com",
  password: "password123",
});



onBeforeMount(async () => {
  await userStore.verifySession();
  if (userStore.isAuthenticated) router.push("/");
});

const currFormToShow = ref(route.query.register == 1 ? "register" : "login");
</script>

<template>
  <div class="bg-bgDark w-screen h-screen p-8">
    <div class="bg-bgAlternate w-full h-full rounded-md border border-white/25 grid grid-rows-3 grid-cols-1 lg:grid-rows-1 lg:grid-cols-2">
      <div class="items-center justify-center border-b lg:border-b-0 lg:border-r border-white/25 relative">
        <div class="absolute inset-0 h-full w-full opacity-20 bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-size-[16px_16px] z-0"></div>
        <div class="z-10 grid grid-cols-1 grid-rows-5 p-10 h-full">
          <div class="row-span-5 lg:row-span-2 z-10 text-2xl lg:text-5xl font-bold flex flex-col text-center gap-5 justify-center items-center">
            <span> <span class="text-white">Skill</span><span class="text-primary">Issue</span> </span>
            <span class="text-2xl text-textWhite">Játssz, tanulj és készülj az érettségire!</span>
          </div>

          <div class="hidden lg:visible row-span-3 lg:grid grid-rows-1 grid-cols-1 z-10 items-center text-center">
            <div>
              <div class="flex items-center justify-center">
                <TopicCard v-for="(card, index) in historyCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="history" :text="card.text" />
              </div>
              <div class="flex items-center justify-center">
                <TopicCard v-for="(card, index) in literatureCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="literature" :text="card.text" />
              </div>
              <div class="flex items-center justify-center">
                <TopicCard v-for="(card, index) in grammarCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="grammar" :text="card.text" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row-span-2 lg:row-span-1 grid grid-cols-1 grid-rows-6 p-10 h-full items-center justify-center">
        <div class="row-span-1 lg:row-span-2 text-2xl lg:text-5xl font-bold text-textWhite flex flex-col text-center gap-5 justify-center items-center">{{ currFormToShow == "login" ? "Bejelentkezés" : "Regisztráció" }}</div>

        <LoginForm v-if="currFormToShow == 'login' && !userStore.isAuthenticated" @switch-form="currFormToShow = 'register'"></LoginForm>
        <RegisterForm v-if="currFormToShow == 'register' && !userStore.isAuthenticated" @switch-form="currFormToShow = 'login'"></RegisterForm>
      </div>
    </div>
  </div>

  <!-- <form v-if="!userStore.isAuthenticated" @submit.prevent="loginSubmit">
    <input type="email" name="email" v-model="formData.email" />
    <input type="password" name="password" v-model="formData.password" />
    <button type="submit">Login</button>
  </form>-->
</template>
