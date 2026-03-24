<script setup>
const props = defineProps({
    questionData: Object,
    index: Number
});

const emit = defineEmits(['delete']);

const handleDelete = () => {
    if (confirm("Biztosan törlöd?")) {
        emit('delete', props.questionData.id);
    }
};
</script>

<template>
    <div
        class="bg-white/[0.03] border border-white/5 p-5 rounded-xl flex justify-between items-center group hover:bg-white/5 transition-all">
        <div class="flex items-center gap-4 text-left">
            <span class="text-white/10 font-mono text-xs">#{{ index + 1 }}</span>
            <div class="flex flex-col">
                <h3 class="text-md font-medium text-white/80 group-hover:text-white transition-colors">
                    {{ questionData.question }}
                </h3>
            </div>
        </div>

        <div class="flex gap-2">
            <button
                class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-white/10 transition-all text-white/40 hover:text-white">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>

            <button @click="handleDelete"
                class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-red-500/20 transition-all text-white/40 hover:text-red-500">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</template>