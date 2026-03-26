<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";
import { RouterLink } from "vue-router";
import Container from "../Generic/Container.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import { toast } from "vue-sonner";

const userStore = useUserStore();
const { isAuthenticated } = storeToRefs(userStore);

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

const isUserDropdownVisible = ref(false);
const dropdownRef = ref(null);

const handleClickOutside = (e) => {
  if (!dropdownRef.value) return;
  if (!dropdownRef.value.contains(e.target)) {
    isUserDropdownVisible.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});

const navItemClass = "font-medium transform ease-out duration-600 motion-safe:hover:scale-108 text-lg";

const logoutClicked = async () => {
  toast.promise(userStore.logout(), {
    loading: "Kijelentkezés folyamatban folyamatban...",
    success: (data) => "Sikeres kijelentkezés",
    error: (error) => {
      if (!error.response) {
        return "Ellenőrizd az internetkapcsolatot.";
      }

      const { status, data } = error.response;

      if (status === 401) return data.message;

      return "Ismeretlen hiba történt.";
    },
  });
};
</script>

<template>
  <nav class="bg-bgDark text-white z-50 fixed w-screen">
    <Container>
      <div class="grid grid-rows-1 grid-cols-3 h-16 items-center">
        <div class="text-2xl md:text-3xl font-bold col-span-1"><span>Skill</span><span class="text-primary">Issue</span>
        </div>
        <div class="flex justify-end col-span-2">
          <div class="text-md">
            <!-- Desktop view -->
            <ul class="hidden lg:flex gap-6 ml-auto">
              <li v-for="(navItem, key) in navItems" :class="navItemClass">
                <button @click="scrollTo(key)" class="hover:text-warning cursor-pointer">{{ navItem }}</button>
              </li>

              <li>
                <div v-if="isAuthenticated" class="relative" ref="dropdownRef">
                  <button @click.stop="isUserDropdownVisible = !isUserDropdownVisible"
                    class="text-warning font-bold hover:textGray cursor-pointer" :class="navItemClass">
                    {{ userStore.user.name }}
                  </button>

                  <transition name="dropdown">
                    <div v-if="isUserDropdownVisible"
                      class="absolute right-0 mt-3 bg-bgAlternate rounded-xl w-44 p-4 flex flex-col gap-2 shadow-2xl">
                      <RouterLink :to="'/profiles/' + userStore.user.id">
                        <button class="text-left hover:text-warning transition w-full cursor-pointer"
                          @click="isUserDropdownVisible = false">Profilom</button>
                      </RouterLink>

                      <RouterLink v-if="userStore.user.is_admin" to="/admin">
                        <button class="text-left hover:text-primary transition w-full cursor-pointer"
                          @click="isUserDropdownVisible = false">
                          Admin panel
                        </button>
                      </RouterLink>

                      <button @click="logoutClicked"
                        class="text-left text-error hover:opacity-80 transition w-full cursor-pointer">Kijelentkezés</button>
                    </div>
                  </transition>
                </div>

                <RouterLink v-else to="/login" :class="navItemClass">
                  <button
                    class="text-warning font-bold hover:text-warning/80 transition-all duration-300 cursor-pointer">
                    Bejelentkezés
                  </button>
                </RouterLink>
              </li>
            </ul>

            <!-- Mobile hamburger button -->
            <button @click="toggleMenu"
              class="lg:hidden flex flex-col items-center justify-center w-6 h-5 ml-auto transform ease-out duration-600 motion-safe:hover:scale-110">
              <i class="fa-solid fa-bars text-2xl"></i>
            </button>

            <!-- Mobile view -->
            <transition name="slide">
              <ul v-if="isOpen"
                class="lg:hidden fixed top-16 right-0 bg-bgDark w-56 h-screen flex flex-col gap-6 p-10 z-50">
                <li v-for="(navItem, key) in navItems"
                  class="transform ease-out duration-600 motion-safe:hover:scale-108">
                  <button @click="
                    scrollTo(key);
                  toggleMenu();
                  ">
                    {{ navItem }}
                  </button>
                </li>

                <li :class="navItemClass">
                  <div v-if="isAuthenticated" class="flex flex-col items-start" ref="dropdownRef">
                    <button @click.stop="isUserDropdownVisible = !isUserDropdownVisible"
                      class="text-warning text-left text-nowrap font-bold hover:textGray cursor-pointer"
                      :class="navItemClass">
                      {{ userStore.user.name }}
                    </button>

                    <transition name="dropdown">
                      <div v-if="isUserDropdownVisible"
                        class="top-5 mt-3 bg-bgAlternate rounded-xl w-full p-4 flex flex-col gap-2 shadow-2xl">
                        <RouterLink :to="'/profiles/' + userStore.user.id">
                          <button class="text-left hover:text-warning transition w-full cursor-pointer"
                            @click="isUserDropdownVisible = false; toggleMenu()">Profilom</button>
                        </RouterLink>

                        <RouterLink v-if="userStore.user.is_admin" to="/admin">
                          <button class="text-left hover:text-primary transition w-full cursor-pointer"
                            @click="isUserDropdownVisible = false; toggleMenu()">
                            Admin panel
                          </button>
                        </RouterLink>

                        <button @click="logoutClicked"
                          class="text-left text-error hover:opacity-80 transition w-full cursor-pointer">Kijelentkezés</button>
                      </div>
                    </transition>
                  </div>
                  <RouterLink v-else to="/login" :class="navItemClass" @click="toggleMenu">
                    <button
                      class="text-warning font-bold hover:text-warning/80 transition-all duration-300 cursor-pointer">
                      Bejelentkezés
                    </button>
                  </RouterLink>
                </li>
              </ul>
            </transition>
          </div>
        </div>
      </div>
    </Container>
  </nav>
</template>
