<script setup>
import Button from "../Generic/Button.vue";
import Container from "../Generic/Container.vue";
</script>

<template>
  <div id="home" class="absolute w-full h-full bg-bgDark grid grid-rows-1 grid-cols-2 items-center">
    <Container>
      <i class="fa-solid fa-graduation-cap text-[500px] text-accentPurple opacity-5 absolute top-50 left-15"></i>
      <h1 class="text-3xl md:text-7xl font-bold text-white text-nowrap">Érettségire fel!</h1>
      <h2 class="text-lg md:text-2xl mt-3 font-bold text-white text-nowrap">Játssz, tanulj és készülj az érettségire!</h2>
      <Button title="Vágjunk bele" class="mt-8 text-lg" />
    </Container>
  </div>
</template>
