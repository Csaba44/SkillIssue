<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView } from 'vue-router';

const isOpen = ref(false)

const scrollTo = (id) => {
  const el = document.getElementById(id)
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}

const toggleMenu = () => {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <nav class="bg-bgDark text-white z-50">
    <div class=" mx-10 px-4">
      <div class="flex h-16 items-center justify-between">
        <div class="text-2xl font-bold">
          SkillIssue
        </div>
        <div class="text-md">

          <!--Asztali nézet-->
          <ul class="hidden md:flex gap-6 ml-auto">
            <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><button @click="scrollTo('homepage')" class="hover:text-warning">
                Kezdőlap
              </button></li>
            <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><button @click="scrollTo('about')" class="hover:text-warning">
                Rólunk
              </button></li>
            <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><button @click="scrollTo('services')" class="hover:text-warning">
                Szolgáltatásaink
              </button></li>
            <li class="transform ease-out duration-600 motion-safe:hover:scale-108">
              <RouterLink to="/login"><button class="text-warning font-bold hover:textGray">Bejelentkezés</button></RouterLink>
            </li>
          </ul>

          <!--Mobilos gomb-->
          <button @click="toggleMenu" class="md:hidden flex flex-col justify-between w-6 h-5 ml-auto transform ease-out duration-600 motion-safe:hover:scale-110">
            <span class="block h-0.5 w-full bg-textWhite"></span>
            <span class="block h-0.5 w-full bg-textWhite"></span>
            <span class="block h-0.5 w-full bg-textWhite"></span>
          </button>

          <!--Mobilos nézet-->
          <transition name="slide">
            <ul v-if="isOpen" class="md:hidden fixed top-16 right-0 bg-bgDark w-56 h-screen flex flex-col gap-6 p-10">
              <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><button @click="scrollTo('homepage'); toggleMenu()" class="hover:text-warning">Kezdőlap</button></li>
              <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><button @click="scrollTo('about'); toggleMenu()" class="hover:text-warning">Rólunk</button></li>
              <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><button @click="scrollTo('services'); toggleMenu()"
                  class="hover:text-warning">Szolgáltatásaink</button></li>
              <li class="transform ease-out duration-600 motion-safe:hover:scale-108"><RouterLink @click="toggleMenu()" to="/login"><button class="text-textWhite font-bold hover:text-warning">Bejelentkezés</button></RouterLink>
              </li>
            </ul>
          </transition>


        </div>
      </div>
    </div>
  </nav>
</template>
