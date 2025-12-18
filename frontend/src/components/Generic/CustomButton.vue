<script setup>
import { defineProps } from 'vue'
import { RouterLink } from 'vue-router'

const props = defineProps({
    title: String,
    to: {
        type: String,
        default: null
    },
})
</script>

<template>

    <RouterLink v-if="to" :to="to">
        <Button class="hover:bg-secondary py-2 px-4 bg-gray-200 rounded-lg border-1 border-gray-300 drop-shadow-lg">
            {{title}}
        </Button>
    </RouterLink>

    <Button style="background-color: var(--color-primary);" v-else class="font-bold text-white py-2 px-10 rounded-full border-1 border-gray-300 drop-shadow-lg">
        {{title}}
    </Button>

</template>