<script setup>
import Button from "../Generic/Button.vue";
import Container from "../Generic/Container.vue";
import TopicCard from "../Generic/TopicCard.vue";
</script>

<template>
  <div id="home"
    class="absolute w-full min-h-full bg-bgDark grid md:grid-cols-2 md:grid-rows-1 grid-cols-1 grid-rows-2 md:items-center overflow-hidden">
    <Container
      class="mt-30 md:mt-0 h-full flex flex-col md:items-start items-center text-center md:text-left justify-center">
      <i class="fa-solid fa-graduation-cap text-[500px] text-accentPurple opacity-5 absolute top-50 left-15"></i>
      <h1 class="text-3xl md:text-7xl font-bold text-white text-nowrap">Érettségire fel!</h1>
      <h2 class="text-lg md:text-2xl mt-3 font-bold text-white w-full">Játssz, tanulj és készülj az érettségire!</h2>
      <Button title="Vágjunk bele" class="mt-8 md:text-lg text-md text-nowrap" />
    </Container>

    <Container class="h-full flex justify-center items-center p-10">
      <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-3 w-full">

        <TopicCard icon="fa-brands fa-font-awesome" :iconTop="20" :iconLeft="1" type="history" text="Államalapítás" />
        <TopicCard icon="fa-solid fa-book" :iconTop="20" :iconLeft="1" type="grammar" text="Nyelvtan" />
        <TopicCard icon="fa-solid fa-flask" :iconTop="20" :iconLeft="1" type="literature" text="Irodalom" />
      </div>
    </Container>


  </div>
</template>
