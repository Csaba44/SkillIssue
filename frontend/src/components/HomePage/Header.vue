<script setup>
import Button from "../Generic/Button.vue";
import Container from "../Generic/Container.vue";
import TopicCard from "../Generic/TopicCard.vue";
</script>

<template>
  <div id="home" class="absolute w-full min-h-full bg-bgDark grid lg:grid-cols-2 lg:grid-rows-1 grid-cols-1 grid-rows-2 lg:items-center overflow-hidden">
    <Container class="mt-30 md:mt-0 h-full flex flex-col md:items-start items-center text-center md:text-left justify-center">
      <i class="fa-solid fa-graduation-cap text-[500px] text-accentPurple opacity-5 absolute top-50 left-15"></i>
      <h1 class="text-3xl md:text-7xl font-bold text-white text-nowrap">Érettségire fel!</h1>
      <h2 class="text-lg md:text-2xl mt-3 font-bold text-white w-full">Játssz, tanulj és készülj az érettségire!</h2>
      <Button title="Vágjunk bele" class="mt-8 md:text-lg text-md text-nowrap" />
    </Container>

    <Container class="relative h-full flex lg:justify-end justify-center items-center p-10">
      <div class="flex lg:flex-col lg:absolute lg:-right-4">
        <div class="flex">
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
        </div>
        <div class="flex justify-end">
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
        </div>
        <div class="flex">
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
          <TopicCard icon="fa-solid fa-flag" :iconTop="15" :iconLeft="-20" :icon-rotate="30" type="history" text="Államalapítás" />
        </div>
      </div>
    </Container>
  </div>
</template>
