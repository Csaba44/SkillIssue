<script setup>
import Widget from "../Generic/Widget.vue";
</script>

<template>
  <div class="w-full flex justify-center items-center">
    <Widget class="rounded-full! px-10 gap-5 grid grid-rows-2 grid-cols-1 sm:grid-rows-1 sm:grid-cols-2 md:grid-cols-3 items-center justify-center">
      <div class="text-center sm:text-left">
        <h1 class="text-xl text-white font-bold">Skill<span class="text-primary">Issue</span></h1>
      </div>

      <nav class="text-textWhite text-lg flex justify-center items-center gap-5 font-medium text-nowrap">
        <p class="cursor-pointer">Ranked</p>
        <p class="cursor-pointer flex gap-1 items-center justify-center">Solo<span class="hidden sm:flex"> gyakorlás</span></p>
      </nav>

      <div class="hidden md:flex gap-5 justify-end">
        <div class="flex flex-col">
          <p>Elo 1776</p>
          <div class="flex justify-between">
            <div class="w-[28%] h-1 bg-textWhite rounded-full flex overflow-hidden">
              <span class="w-full h-full bg-accentGreen"></span>
            </div>
            <div class="w-[28%] h-1 bg-textWhite rounded-full flex overflow-hidden">
              <span class="w-full h-full bg-accentGreen"></span>
            </div>
            <div class="w-[28%] h-1 bg-textWhite rounded-full flex overflow-hidden">
              <span class="w-[50%] h-full bg-accentGreen"></span>
            </div>
          </div>
        </div>
        <div>
          <span class="text-lg font-medium">3</span>
          <i class="fa-regular fa-fire text-primary text-lg"></i>
        </div>
      </div>
    </Widget>
  </div>
</template>
