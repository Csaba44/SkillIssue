<script setup>
const props = defineProps({
    report: Object
});
const emit = defineEmits(['view', 'delete']);

const statusTranslations = {
    Open: 'Nyitott',
    Investigating: 'Vizsgálat',
    Closed: 'Lezárva'
};

const statusConfig = {
    Open: {
        icon: 'fa-solid fa-triangle-exclamation',
        colorClass: 'bg-red-500/10 text-red-500'
    },
    Investigating: {
        icon: 'fa-solid fa-magnifying-glass',
        colorClass: 'bg-yellow-500/10 text-yellow-500'
    },
    Closed: {
        icon: 'fa-solid fa-envelope',
        colorClass: 'bg-green-500/10 text-green-500'
    }
};
</script>

<template>
    <div
        class="bg-white/3 border border-white/5 p-5 rounded-xl flex justify-between items-center group hover:bg-white/5 transition-all">
        <div class="flex items-center gap-6 text-left">
            <div :class="[
                'w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300',
                statusConfig[report.status]?.colorClass || 'bg-white/10 text-white'
            ]">
                <i :class="statusConfig[report.status]?.icon || 'fa-solid fa-question'"></i>
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