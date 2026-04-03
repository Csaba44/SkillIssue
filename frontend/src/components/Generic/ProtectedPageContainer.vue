<script setup>
import { useUserStore } from "../../stores/UserStore";
import UserBanned from "./UserBanned.vue";

const props = defineProps({
  class: String,
});

const userStore = useUserStore();
</script>

<template>
  <user-banned v-if="userStore.user.ban_status" :reason="userStore.user.ban_status?.reason" :release-date="userStore.user.ban_status?.release_date" />

  <div v-else class="bg-bgDark w-screen min-h-screen p-8 flex">
    <div class="bg-bgAlternate w-full rounded-md border border-white/25 min-h-full p-5" :class="props.class">
      <slot></slot>
    </div>
  </div>
</template>
