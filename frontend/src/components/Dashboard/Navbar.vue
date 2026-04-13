<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import Widget from "../Generic/Widget.vue";
import { useUserStore } from "../../stores/UserStore";
import { storeToRefs } from "pinia";
import { RouterLink } from "vue-router";
import { toast } from "vue-sonner";
import { useRouter } from "vue-router";

const props = defineProps({
  minimal: {
    type: Boolean,
    default: false,
  },
});

const router = useRouter();
const userStore = useUserStore();
const { user, isAuthenticated } = storeToRefs(userStore);

const isUserDropdownVisible = ref(false);
const dropdownRef = ref(null);

const handleClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isUserDropdownVisible.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});

const logoutClicked = async () => {
  toast.promise(userStore.logout(), {
    loading: "Kijelentkezés folyamatban...",
    success: () => {
      router.push("/");
      return "Sikeres kijelentkezés";
    },
    error: "Hiba történt a kijelentkezéskor.",
  });
};

const eloToNextRank = computed(() => {
  if (!user.value?.next_rank) return 0;
  return user.value.next_rank.min_elo - user.value.elo;
});
</script>

<template>
  <div class="w-full flex justify-center items-center relative z-50">
    <Widget class="rounded-full! px-10 py-4 w-full max-w-6xl grid grid-cols-1 md:grid-cols-3 items-center relative">
      <div class="text-center md:text-left">
        <RouterLink to="/" class="text-xl text-white font-bold">Skill<span class="text-primary">Issue</span> </RouterLink>
      </div>

      <div class="hidden md:flex justify-center">
        <div v-if="!minimal" class="flex flex-col items-center w-80 relative group">
          <p class="text-sm text-white/60">
            {{ user.rank.name }} • <span class="text-white font-semibold">{{ user.elo }}</span> Elo
          </p>

          <div class="w-full h-2 bg-white/10 rounded-full mt-2 overflow-hidden">
            <div
              class="h-full bg-linear-to-r from-accentPurple via-accentGreen to-accentGreen transition-all duration-500"
              :style="{
                width: ((user.elo - user.rank.min_elo) / (user.next_rank.min_elo - user.rank.min_elo)) * 100 + '%',
              }"
            ></div>
          </div>

          <div class="absolute top-10 bg-bgDark text-textWhite text-xs px-3 py-1 rounded-md opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 pointer-events-none whitespace-nowrap">{{ eloToNextRank }} Elo a következő rangig</div>
        </div>
      </div>

      <div class="flex justify-center md:justify-end items-center gap-6 mt-4 md:mt-0">
        <div v-if="!minimal" class="flex items-center gap-2">
          <span class="text-lg font-medium">{{ user.streak_count }}</span>
          <i class="fa-solid fa-fire text-primary text-lg"></i>
        </div>

        <div v-if="isAuthenticated" class="relative" ref="dropdownRef">
          <button @click.stop="isUserDropdownVisible = !isUserDropdownVisible" class="text-lg font-medium hover:text-primary cursor-pointer transition-colors">
            {{ user.name }}
            <i class="fa-solid fa-chevron-down text-xs ml-1 opacity-50"></i>
          </button>

          <transition name="dropdown">
            <div v-if="isUserDropdownVisible" class="absolute right-0 mt-4 bg-bgAlternate rounded-xl w-44 p-4 flex flex-col gap-2 shadow-2xl border border-white/5 z-[100]">
              <RouterLink :to="'/profiles/' + user.id">
                <button class="text-left hover:text-warning transition w-full cursor-pointer" @click="isUserDropdownVisible = false">Profilom</button>
              </RouterLink>

              <RouterLink v-if="userStore.user.is_admin" to="/admin">
                <button class="text-left hover:text-warning transition w-full cursor-pointer" @click="isUserDropdownVisible = false">Admin panel</button>
              </RouterLink>

              <button @click="logoutClicked" class="text-left text-error hover:opacity-80 transition w-full cursor-pointer">Kijelentkezés</button>
            </div>
          </transition>
        </div>
      </div>
    </Widget>
  </div>
</template>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
