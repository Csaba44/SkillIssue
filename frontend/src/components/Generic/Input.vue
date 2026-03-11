<script setup>
const model = defineModel();
const props = defineProps({
  class: String,
  icon: String,
  accentColor: {
    type: String,
    required: false,
    default: "accentPurple",
  },
  name: {
    type: String,
    required: true,
  },
  placeholder: String,
  type: {
    type: String,
    required: false,
    default: "text",
  },
  autocomplete: String,
});
</script>

<template>
  <div class="flex min-w-30 h-12 bg-bgDark border border-white/25 rounded-md" :class="props.class">
    <div v-if="props.icon" class="flex items-center justify-center px-2">
      <i :class="`${props.icon} text-${props.accentColor}`" class="text-xl"></i>
      <span :class="`bg-${props.accentColor}`" class="ml-2 h-6 w-0.5"></span>
    </div>
    <input v-model="model" :autocomplete="props.autocomplete" :name="props.name" class="text-textWhite w-full outline-none" :type="props.type" :placeholder="props.placeholder" />
  </div>
</template>
