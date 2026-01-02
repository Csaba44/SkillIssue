<script setup>
import Button from "../Generic/Button.vue";
import Container from "../Generic/Container.vue";
import TopicCard from "../Generic/TopicCard.vue";
import { historyCards, literatureCards, grammarCards } from "../../utils/TopicCardList";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import { RouterLink } from "vue-router";
import { ref, onMounted } from "vue";

const isAnimating = ref(false);

onMounted(() => {
  setTimeout(() => {
    isAnimating.value = true;
  }, 400);
});

const userStore = useUserStore();
const { isAuthenticated } = storeToRefs(userStore);
</script>

<template>
  <div id="home" class="w-full h-screen bg-bgDark grid lg:grid-cols-2 lg:grid-rows-1 grid-cols-1 grid-rows-2 lg:items-center overflow-hidden">
    <Container class="mt-30 md:mt-0 h-full flex flex-col md:items-start items-center text-center md:text-left justify-center">
      <i class="fa-solid fa-graduation-cap text-[700px] text-accentPurple opacity-5 fixed top-30 left-15"></i>
      <h1 class="text-3xl md:text-2xl xl:text-8xl font-bold text-white text-nowrap">Érettségire fel!</h1>
      <h2 class="text-lg md:text-xl lg:text-3xl mt-3 font-bold text-white w-full">Játssz, tanulj és készülj az érettségire!</h2>
      <RouterLink :to="isAuthenticated ? '/dashboard' : '/login?register=1'"><Button title="Vágjunk bele" class="mt-8 md:text-lg text-md text-nowrap" /></RouterLink>
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
      <div class="absolute bottom-6 right-6 hidden lg:flex gap-4 z-10">
        <h1 class="text-6xl pb-5 mt-10 md:mt-70 font-bold text-white text-nowrap">Rólunk</h1>
      </div>
    </Container>

    <!-- MOBILE VIEW X SCROLL ANIM -->
    <Container class="lg:hidden flex items-end mb-15">
      <div class="overflow-hidden py-2 pb-8">
        <div class="flex w-max animate-scroll-x">
          <TopicCard v-for="(card, index) in historyCards" :key="index" :isAnimated="isAnimating" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="history" :text="card.text" />
          <TopicCard v-for="(card, index) in literatureCards" :key="index" :isAnimated="isAnimating" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="literature" :text="card.text" />
          <TopicCard v-for="(card, index) in grammarCards" :key="index" :isAnimated="isAnimating" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="grammar" :text="card.text" />
          <TopicCard v-for="(card, index) in historyCards" :key="index" :isAnimated="isAnimating" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="history" :text="card.text" />
          <TopicCard v-for="(card, index) in literatureCards" :key="index" :isAnimated="isAnimating" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="literature" :text="card.text" />
          <TopicCard v-for="(card, index) in grammarCards" :key="index" :isAnimated="isAnimating" :icon="card.icon" :iconTop="card.iconTop" :iconLeft="card.iconLeft" :icon-rotate="card.iconRotate" type="grammar" :text="card.text" />
        </div>
      </div>
    </Container>
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
      <svg class="w-full h-20 block" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="rgb(229, 231, 235)" fill-opacity="1" d="M0,224L60,197.3C120,171,240,117,360,90.7C480,64,600,64,720,101.3C840,139,960,213,1080,229.3C1200,245,1320,203,1380,181.3L1440,160L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
      </svg>
    </div>
  </div>
</template>
