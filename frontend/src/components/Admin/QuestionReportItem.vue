<script setup>
const props = defineProps({
    report: Object
});
const emit = defineEmits(['view', 'delete']);
</script>

<template>
    <div
        class="bg-white/[0.03] border border-white/5 p-5 rounded-xl flex justify-between items-center group hover:bg-white/5 transition-all">
        <div class="flex items-center gap-6 text-left">
            <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div class="flex flex-col">
                <h3 class="text-md font-medium text-white/80 group-hover:text-white transition-colors">
                    {{ report.question?.question || 'Törölt kérdés' }}
                </h3>
                <div class="flex gap-3 mt-1">
                    <span class="text-xs text-white/30 italic">
                        Bejelentő: {{ report.user?.name || 'Ismeretlen' }}
                    </span>
                    <span class="text-xs text-accentPurple/60 font-mono">
                        ID: #{{ report.id }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button @click="emit('view', report)"
                class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white/60 hover:text-white transition-all text-xs font-bold uppercase">
                Részletek
            </button>
            <button @click="emit('delete', report.id)"
                class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-red-500/20 text-white/20 hover:text-red-500 transition-all">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</template>