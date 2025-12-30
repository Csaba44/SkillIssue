<script setup>
import Button from "../Generic/Button.vue";
import Input from "../Generic/Input.vue";

const emit = defineEmits(["switch-form"]);
</script>

<template>
  <form class="row-span-4 py-5 flex flex-col gap-3 items-center justify-center h-full w-full ">
    <Input name="email" autocomplete="email" placeholder="gipsz.jakab@gmail.com" icon="fa-solid fa-at" class="w-full! lg:w-[50%]!" />
    <Input name="password" autocomplete="password" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
    <Button title="Bejelentkezés" class="mt-3"></Button>
    <a class="text-accentYellow cursor-pointer hover:underline" @click="emit('switch-form')">Nincs még fiókja?</a>
  </form>
</template>
