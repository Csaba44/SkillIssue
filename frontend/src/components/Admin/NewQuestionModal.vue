<script setup>
import { ref } from 'vue';
import api from "../../config/api";
import { toast } from "vue-sonner";
import Button from "../Generic/Button.vue";

const emit = defineEmits(['close', 'refresh']);
const props = defineProps({ availableSubjects: Array });

const formData = ref({
    question: "",
    subject_id: "",
    answers: [
        { answer: "", is_correct: true },
        { answer: "", is_correct: false },
        { answer: "", is_correct: false },
        { answer: "", is_correct: false },
    ]
});

const isSubmitting = ref(false);

const submitForm = async () => {
    if (!formData.value.question || !formData.value.subject_id) {
        return toast.error("Kérdés és tantárgy választása kötelező!");
    }

    isSubmitting.value = true;
    try {
        const res = await api.post("/api/questions", {
            question: formData.value.question,
            subject_id: Number(formData.value.subject_id)
        });

        const newQuestionId = res.data.question?.id;

        await api.post(`/api/questions/${newQuestionId}/answer`, {
            answers: formData.value.answers
        });

        toast.success("Kérdés sikeresen rögzítve!");
        emit('refresh');
        emit('close');

    } catch (error) {
        console.error("Hiba történt:", error);
        const errorMsg = error.response?.data?.message || error.message;
        toast.error("Mentési hiba: " + errorMsg);
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div
            class="bg-bgDark border border-white/10 w-full max-w-2xl rounded-3xl p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3"><i
                        class="fa-solid fa-plus text-accentGreen"></i>Új kérdés</h2>
                <button @click="emit('close')" class="text-white/20 hover:text-white transition-colors"><i
                        class="fa-solid fa-xmark text-2xl"></i></button>
            </div>

            <div class="space-y-6 text-left">
                <div>
                    <label
                        class="text-white/40 text-[10px] uppercase font-bold tracking-widest block mb-2">Tantárgy</label>
                    <select v-model="formData.subject_id"
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white outline-none focus:border-accentGreen transition-all cursor-pointer">
                        <option value="" disabled>Válassz tantárgyat...</option>
                        <option v-for="s in availableSubjects" :key="s.id" :value="s.id" class="bg-bgDark">{{ s.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label
                        class="text-white/40 text-[10px] uppercase font-bold tracking-widest block mb-2">Kérdés</label>
                    <textarea v-model="formData.question" placeholder="Mi a kérdés?" rows="2"
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white outline-none focus:border-accentGreen transition-all resize-none" />
                </div>

                <div class="space-y-3">
                    <label class="text-white/40 text-[10px] uppercase font-bold tracking-widest block">Válaszok (Az első
                        a helyes)</label>
                    <div v-for="(ans, i) in formData.answers" :key="i" class="flex items-center gap-3">
                        <div :class="i == 0 ? 'bg-accentGreen shadow-[0_0_10px_rgba(34,197,94,0.3)]' : 'bg-white/10'"
                            class="w-1.5 h-10 rounded-full"></div>
                        <input v-model="ans.answer" :placeholder="i == 0 ? 'Helyes válasz...' : 'Hibás válasz...'"
                            class="flex-1 bg-white/5 border border-white/10 rounded-xl p-3 text-white outline-none focus:border-white/20 text-sm" />
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6">
                    <button @click="emit('close')"
                        class="px-6 py-3 text-white/40 font-bold uppercase text-xs hover:text-white transition-all">Mégse</button>
                    <Button @click="submitForm" :title="isSubmitting ? 'Mentés...' : 'Mentés'"
                        class="bg-accentGreen text-black font-extrabold px-10 py-3 rounded-full hover:scale-105 transition-all shadow-lg shadow-accentGreen/10" />
                </div>
            </div>
        </div>
    </div>
</template>