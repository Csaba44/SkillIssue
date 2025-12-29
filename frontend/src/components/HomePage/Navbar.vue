<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";

const isOpen = ref(false);

const scrollTo = (id) => {
  const el = document.getElementById(id);
  if (el) el.scrollIntoView({ behavior: "smooth" });
};

const toggleMenu = () => {
  isOpen.value = !isOpen.value;
};

const navItems = {
  home: "Kezdőlap",
  about: "Rólunk",
  services: "Szolgáltatásaink",
};

const navItemClass = "transform ease-out duration-600 motion-safe:hover:scale-108";
</script>

<template>
  <nav class="bg-bgDark text-white z-50">
    <div class="px-5 md:px-10">
      <div class="flex h-16 items-center justify-between">
        <div class="text-2xl font-bold"><span>Skill</span><span class="text-primary">Issue</span></div>
        <div class="text-md">
          <!--Asztali nézet-->
          <ul class="hidden md:flex gap-6 ml-auto">
            <li v-for="(navItem, key) in navItems" :class="navItemClass">
              <button @click="scrollTo(key)" class="hover:text-warning">{{ navItem }}</button>
            </li>

            <li :class="navItemClass">
              <RouterLink to="/login"><button class="text-warning font-bold hover:textGray">Bejelentkezés</button></RouterLink>
            </li>
          </ul>

          <!--Mobilos gomb-->
          <button @click="toggleMenu" class="md:hidden flex flex-col items-center justify-center w-6 h-5 ml-auto transform ease-out duration-600 motion-safe:hover:scale-110">
            <i class="fa-solid fa-bars text-2xl"></i>
          </button>

          <!--Mobilos nézet-->
          <transition name="slide">
            <ul v-if="isOpen" class="md:hidden fixed top-16 right-0 bg-bgDark w-56 h-screen flex flex-col gap-6 p-10 z-50">
              <li v-for="(navItem, key) in navItems" class="transform ease-out duration-600 motion-safe:hover:scale-108">
                <button
                  @click="
                    scrollTo(key);
                    toggleMenu();
                  "
                >
                  {{ navItem }}
                </button>
              </li>

              <li :class="navItemClass">
                <RouterLink to="/login"><button class="text-warning font-bold hover:textGray">Bejelentkezés</button></RouterLink>
              </li>
            </ul>
          </transition>
        </div>
      </div>
    </div>
  </nav>
</template>
