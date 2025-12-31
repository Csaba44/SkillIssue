<script setup>
import Button from "../Generic/Button.vue";
import Container from "../Generic/Container.vue";
import TopicCard from "../Generic/TopicCard.vue";
import { historyCards, literatureCards, grammarCards } from "../../utils/TopicCardList";
</script>

<template>
  <div id="home" class="absolute w-full min-h-full bg-bgDark grid lg:grid-cols-2 lg:grid-rows-1 grid-cols-1 grid-rows-2 lg:items-center overflow-hidden">
    <Container class="mt-30 md:mt-0 h-full flex flex-col md:items-start items-center text-center md:text-left justify-center">
      <i class="fa-solid fa-graduation-cap text-[700px] text-accentPurple opacity-5 absolute top-30 left-15"></i>
      <h1 class="text-3xl md:text-2xl xl:text-8xl font-bold text-white text-nowrap">Érettségire fel!</h1>
      <h2 class="text-lg md:text-xl lg:text-3xl mt-3 font-bold text-white w-full">Játssz, tanulj és készülj az érettségire!</h2>
      <Button title="Vágjunk bele" class="mt-8 md:text-lg text-md text-nowrap" />
    </Container>

    <Container class="hidden lg:flex relative h-full lg:justify-end justify-center items-center p-10">
      <div class="flex lg:flex-col lg:absolute lg:-right-4">
        <div class="flex">
          <div class="flex">
            <TopicCard v-for="(card, index) in historyCards" v-show="!card.isInvisibleOnHeader" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="history" :text="card.text" />
          </div>
        </div>
        <div class="flex lg:justify-end">
          <TopicCard v-for="(card, index) in literatureCards" v-show="!card.isInvisibleOnHeader" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="literature" :text="card.text" />
        </div>
        <div class="flex">
          <TopicCard v-for="(card, index) in grammarCards" v-show="!card.isInvisibleOnHeader" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="grammar" :text="card.text" />
        </div>
      </div>
    </Container>

    <!-- MOBILE VIEW X SCROLL ANIM -->
    <Container class="lg:hidden flex items-end mb-5">
      <div class="overflow-hidden py-2">
        <div class="flex w-max animate-scroll-x">
          <TopicCard v-for="(card, index) in historyCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="history" :text="card.text" />
          <TopicCard v-for="(card, index) in literatureCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="literature" :text="card.text" />
          <TopicCard v-for="(card, index) in grammarCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="grammar" :text="card.text" />
          <TopicCard v-for="(card, index) in historyCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="history" :text="card.text" />
          <TopicCard v-for="(card, index) in literatureCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="literature" :text="card.text" />
          <TopicCard v-for="(card, index) in grammarCards" :key="index" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="grammar" :text="card.text" />
        </div>
      </div>
    </Container>
  </div>
</template>
