<script setup>
import { ref, computed } from "vue";

const model = defineModel();
const props = defineProps({
  options: { type: Array, required: true }, // [{ value, label }]
  icon: { type: String, default: "fa-solid fa-chevron-down" },
  accentColor: { type: String, default: "accentPurple" },
  class: String,
});

const isOpen = ref(false);
const selectedLabel = computed(() => props.options.find((o) => o.value === model.value)?.label ?? "");

const select = (value) => {
  model.value = value;
  isOpen.value = false;
};
</script>

<template>
  <div class="relative" :class="props.class">
    <button
      type="button"
      @click="isOpen = !isOpen"
      class="flex items-center w-full min-w-30 h-12 bg-bgDark border border-white/25 rounded-md cursor-pointer transition-colors duration-200"
      :class="isOpen ? 'border-white/50' : ''"
    >
      <div class="flex items-center justify-center px-3">
        <i :class="`fa-solid fa-hashtag text-${props.accentColor} text-xl`"></i>
        <span :class="`bg-${props.accentColor}`" class="ml-2 h-6 w-0.5"></span>
      </div>
      <span class="text-textWhite text-sm flex-1 text-left px-2">{{ selectedLabel }}</span>
      <i
        class="fa-solid fa-chevron-down text-white/30 text-xs pr-6 transition-transform duration-200"
        :class="isOpen ? 'rotate-180' : ''"
      ></i>
    </button>

    <Transition name="dropdown">
      <div
        v-if="isOpen"
        class="absolute z-50 w-full mt-1 bg-bgDark border border-white/20 rounded-xl shadow-2xl shadow-black/40 overflow-hidden"
      >
        <button
          v-for="option in options"
          :key="option.value"
          type="button"
          @click="select(option.value)"
          class="w-full flex items-center gap-3 px-4 py-3 text-sm text-left transition-colors duration-150 cursor-pointer"
          :class="
            model === option.value
              ? `text-${props.accentColor} bg-white/10`
              : 'text-white/70 hover:bg-white/5 hover:text-white'
          "
        >
          <i v-if="model === option.value" :class="`fa-solid fa-check text-${props.accentColor} text-xs`"></i>
          <i v-else class="fa-regular fa-circle text-white/20 text-xs"></i>
          {{ option.label }}
        </button>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition:
    opacity 0.15s ease,
    transform 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
