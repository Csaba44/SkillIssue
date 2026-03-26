<script setup>
import { computed, ref } from 'vue';
import api from "../../config/api";
import { toast } from "vue-sonner";
import Button from "../Generic/Button.vue";
import Select from "../Generic/Select.vue";

const props = defineProps({
    availableSubjects: Array,
    editData: Object
});

const subjectsArray = computed(() => {
    return props.availableSubjects.map((subject) => ({ label: subject.name, value: subject.id }))
})

const emit = defineEmits(['close', 'refresh']);

const isEdit = !!props.editData;
const isSubmitting = ref(false);

const formData = ref({
    question: props.editData?.question || "",
    subject_id: props.editData?.subject_id || "",
    answers: (props.editData && props.editData.answers && props.editData.answers.length > 0)
        ? props.editData.answers.map(a => ({
            id: a.id,
            answer: a.answer,
            is_correct: a.is_correct
        }))
        : [
            { answer: "", is_correct: true },
            { answer: "", is_correct: false },
            { answer: "", is_correct: false },
            { answer: "", is_correct: false },
        ]
});

const submitForm = async () => {
    formData.value.answers.forEach((ans, index) => {
        ans.is_correct = (index == 0);
    });

    if (!formData.value.question || !formData.value.subject_id) {
        return toast.error("Minden mező kitöltése kötelező!");
    }

    if (!formData.value.question || !formData.value.subject_id) {
        return toast.error("Minden mező kitöltése kötelező!");
    }

    isSubmitting.value = true;
    try {
        if (isEdit) {
            await api.put(`/api/questions/${props.editData.id}`, formData.value);
            toast.success("Kérdés sikeresen frissítve!");
        } else {
            await api.post("/api/questions", formData.value);
            toast.success("Kérdés sikeresen létrehozva!");
        }

        emit('refresh');
        emit('close');
    } catch (error) {
        console.error("Hiba:", error);
        toast.error("Hiba történt a mentés során.");
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div
            class="bg-bgDark border border-white/10 w-full max-w-2xl rounded-3xl p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i :class="isEdit ? 'fa-solid fa-pen text-primary' : 'fa-solid fa-plus text-accentGreen'"></i>
                    {{ isEdit ? 'Kérdés szerkesztése' : 'Új kérdés hozzáadása' }}
                </h2>
                <button @click="emit('close')" class="text-white/20 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <div class="space-y-6 text-left">
                <div v-if="!isEdit">
                    <label
                        class="text-white/40 text-[10px] uppercase font-bold tracking-widest block mb-2">Tantárgy</label>

                    <Select :options="subjectsArray"
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white outline-none focus:border-accentGreen transition-all"
                        v-model="formData.subject_id"></Select>
                </div>

                <div>
                    <label
                        class="text-white/40 text-[10px] uppercase font-bold tracking-widest block mb-2">Kérdés</label>
                    <textarea v-model="formData.question" placeholder="Írd be a kérdést..." rows="2"
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white outline-none focus:border-accentGreen transition-all resize-none" />
                </div>

                <div class="space-y-3">
                    <label class="text-white/40 text-[10px] uppercase font-bold tracking-widest block">Válaszok (Az első
                        a helyes)</label>
                    <div v-for="(ans, i) in formData.answers" :key="i" class="flex items-center gap-3">
                        <div :class="i == 0 ? 'bg-accentGreen shadow-[0_0_10px_rgba(34,197,94,0.3)]' : 'bg-white/10'"
                            class="w-1.5 h-10 rounded-full"></div>
                        <input v-model="ans.answer"
                            class="flex-1 bg-white/5 border border-white/10 rounded-xl p-3 text-white outline-none text-sm" />
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6">
                    <button @click="emit('close')" class="px-6 py-3 text-white/40 font-bold uppercase text-xs 
               transition-all duration-200 ease-in-out
               hover:text-white hover:scale-105 
               active:scale-95 active:opacity-70 cursor-pointer">
                        Mégse
                    </button>
                    <Button @click="submitForm"
                        :title="isSubmitting ? 'Folyamatban...' : (isEdit ? 'Változtatások mentése' : 'Kérdés rögzítése')"
                        class="bg-accentGreen text-black font-extrabold px-10 py-3 rounded-full" />
                </div>
            </div>
        </div>
    </div>
</template>