<script setup>
import router from "../config/router";
import Navbar from "../components/HomePage/Navbar.vue";
import Header from "../components/HomePage/Header.vue";
import Container from "../components/Generic/Container.vue";
import wave from "../assets/wave.svg";
import AboutCard from "../components/HomePage/AboutCard.vue";
</script>

<template>
  <Navbar></Navbar>
  <Header></Header>

  <div class="h-screen bg-gray-100">
    <div class="z-0 top-0 left-0 block leading-none w-screen">
      <svg class="w-screen h-auto block" width="1920" height="242" viewBox="0 0 1920 242" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 242L80 213.675C160 185.047 320 128.853 480 128.625C640 128.853 800 185.047 960 205.55C1120 226.053 1280 209.347 1440 209.575C1600 209.347 1760 226.053 1840 233.875L1920 242V-1H1840C1760 -1 1600 -1 1440 -1C1280 -1 1120 -1 960 -1C800 -1 640 -1 480 -1C320 -1 160 -1 80 -1H0V242Z"
          fill="#030F39" />
      </svg>
      <h1 class="text-6xl pb-5 font-bold text-bgDark  text-nowrap text-right mr-13">Rólunk</h1>
    </div>


    <Container class="w-full h-min relative flex items-center justify-center">
      <div class="grid gap-6 grid-cols-1 md:grid-cols-2 sm:grid-cols-2 xl:grid-cols-4">
        <AboutCard></AboutCard>
        <AboutCard class="mt-50" :type="'green'"></AboutCard>
        <AboutCard :type="'orange'"></AboutCard>
        <AboutCard class="mt-50" :type="'yellow'"></AboutCard>
      </div>

    </Container>
  </div>
</template>
