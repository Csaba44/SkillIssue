<script setup>
import Button from "../Generic/Button.vue";
import Checkbox from "../Generic/Checkbox.vue";
import Input from "../Generic/Input.vue";

const emit = defineEmits(["switch-form"]);
</script>

<template>
  <form class="row-span-4 py-5 flex flex-col gap-3 items-center justify-center h-full w-full">
    <Input name="text" autocomplete="name" placeholder="Gipsz Jakab" icon="fa-solid fa-user" class="w-full! lg:w-[50%]!" />
    <Input name="email" autocomplete="email" placeholder="gipsz.jakab@gmail.com" icon="fa-solid fa-at" class="w-full! lg:w-[50%]!" />
    <Input name="password" autocomplete="off" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
    <Input name="password2" autocomplete="off" type="password" placeholder="******" icon="fa-solid fa-key-skeleton" class="w-full! lg:w-[50%]!" />
    <div class="max-w-105">
      <Checkbox @state-change="(isChecked) => {console.log(isChecked.value)}"><span class="text-textWhite text-xs">Kijelentem, hogy az <a class="text-accentYellow cursor-pointer hover:underline">adatkezelési tájékoztatót</a> elolvastam, és elfogadom.</span></Checkbox>
    </div>
    <Button title="Regisztráció" class="mt-3"></Button>
    <a class="text-accentYellow cursor-pointer hover:underline" @click="emit('switch-form')">Már van fiókja?</a>
  </form>
</template>
