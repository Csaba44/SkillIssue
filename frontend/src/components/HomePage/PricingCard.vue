<script setup>
import { defineProps } from "vue";
import Button from "../Generic/Button.vue";

const props = defineProps({
    subscription: {
        type: String,
        default: "Alapcsomag",
    },
    price: {
        type: String,
        default: 0,
    },
    features: {
        type: Array,
        required: true,
    },
    buttonTitle: {
        type: String,
        default: "Vágjunk bele",
    },
    buttonDisabled:  {
        type: Boolean,
        default: false,
    }
});

</script>


<template>
    <div class="bg-white text-bgDark rounded-2xl shadow-lg p-6 w-full max-w-sm pb-10 sm:mb-10">
        <div>
            <span class="text-lg text-gray-500">{{ subscription }}</span>

            <h3 class="text-5xl font-bold mt-2">
                {{ price }} Ft<span class="text-base font-medium">/ hónap</span>
            </h3>

            <hr class="my-4">
            <ul class="space-y-5">
                <li v-for="(feature, index) in features" :key="index">
                    <i class="fa-sharp fa-solid fa-octagon-check text-accentGreen text-2xl"></i> <span class="text-xl">
                        {{ feature }}</span>
                </li>
            </ul>
        </div>
        <div class="flex justify-center mt-8">
            <Button :title="props.buttonTitle" :disabled="props.buttonDisabled" class="md:text-lg text-md text-nowrap" />
        </div>
    </div>
</template>
